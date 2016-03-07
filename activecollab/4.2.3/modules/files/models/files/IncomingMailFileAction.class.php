<?php
  
  /**
   * Incoming mail action - Add Files
   * 
   * @package angie.framework.email
   * @subpackage models.incoming_mail_actions
   *
   */
  class IncomingMailFileAction extends IncomingMailProjectObjectAction {
    
    /**
     * Required additional settings needed for actions
     * 
     * @var array
     */
     protected $required_additional_settings = array('project_id');
    
    /*
     * Constructor
     */
    function __construct() {
      $this->setActionClassName(__CLASS__);
      $this->setSettings();
    }//__construct
    
    /**
     * Render project elements into filter form
     * 
     */
    function renderProjectElements(IUser $user, Project $project, IncomingMailFilter $filter = null) {
      if($filter instanceof IncomingMailFilter) {
        //set initial values from this array
        $this->action_parameters = $filter->getActionParameters();
      }//if
      $object = new File();
      $object->setProject($project);
      $this->addCategorySelect($user, 'AssetCategory', $project);
      $this->addNotifyElement($user,$object);
      $this->addMilestoneSelect($user, $project);
      return $this->elements;
    }//renderProjectElements
    
    
    /**
     * Set settings as name, descriptions..
     */
    public function setSettings() {
      $this->setName(lang('Add New File'));
      $this->setDescription(lang('Add new file to specific project.'));
      $this->setTemplateName('incoming_mail_add_file_action');
      $this->setCanUse(true);
      $this->setModuleName(FILES_MODULE);
      $this->setPreSelected(false);
    }//setSettings
    
    /**
     * Do actions over incoming email
     *
     * @params $incoming_mail 
     * @params array $additional_settings
     * 
     */
    public function doActions(IncomingMail $incoming_email, $additional_settings = false, $force = false) {
      //check all parameters
      $this->checkActionsParameters($incoming_email,$additional_settings);
      
      //========= put custom actions here ==============//
   
      $project_id = $additional_settings['project_id'];
      $project = Projects::findById($project_id);
     
      if(!$project instanceof Project || $project->getState() == STATE_DELETED) {
        throw new Error(IncomingMessageImportErrorActivityLog::ERROR_PROJECT_DOES_NOT_EXISTS);
      }//if
      
       //get users from cc and bcc and users from filter subscribers,notify_people
      $subscribe_users = $this->getUsersToSubscribe($incoming_email,$additional_settings);
      //subscribe leader
      $subscribe_users[] = $project->getLeader();
      
      //get sender
      if(!$incoming_email->getCreatedById() == 0) {
        $sender = Users::findById($incoming_email->getCreatedById());
      } else {
        //if anonymous user creates task
        $sender = new AnonymousUser($incoming_email->getCreatedByName(),$incoming_email->getCreatedByEmail());
      }//if
      
      //is create as option chosen set specific user else set sender as creator
      $create_as = $additional_settings['create_as'];
      
      if($create_as == IncomingMailFilter::CREATE_AS_SPECIFIC_USER) {
        $create_as_user_id = $additional_settings['create_as_user'];
        $create_as_user = Users::findById($create_as_user_id);
        $subscribe_users[] = $create_as_user;
      } else {
        $create_as_user = $sender; 
      }//if
      
      //check to see if user can add task to project
      $allow_for_everyone = $additional_settings['allow_for_everyone'];
      if($allow_for_everyone == IncomingMailFilter::ALLOW_FOR_PEOPLE_WHO_CAN) {
        if(!ProjectAssets::canAdd($sender,$project) ) {
          throw new Error(IncomingMessageImportErrorActivityLog::ERROR_USER_CANNOT_CREATE_OBJECT);
        } //if
      } //if

      //check to see if there is enough disk space for importing attachments from this email
      if(!DiskSpace::canImportEmailBasedOnDiskLimitation($incoming_email)) {
        throw new Error(IncomingMessageImportErrorActivityLog::ERROR_DISK_QUOTA_REACHED);
      } //if

      $attachments = $incoming_email->getAttachments();
      
      if(is_foreachable($attachments)) {
        foreach($attachments as $attachment) {
          
          $file = new File();
          
          $file->setVisibility($project->getDefaultVisibility());
          $file->setName($attachment->getOriginalFilename());
          $file->setBody($incoming_email->getBody());
          $file->setProject($project);
          $file->setSize($attachment->getFileSize());
          $file->setLocation($attachment->getTemporaryFilename());    					
          $file->setMimeType($attachment->getContentType());
          $file->setState(STATE_VISIBLE);
          $file->setCreatedBy($create_as_user);
          //$file->setSource(OBJECT_SOURCE_EMAIL);
          $file->setCreatedOn($incoming_email->getCreatedOn());
          $file->setVersionNum(1);
           
          //Set additional settings 
          $file->setCategoryId($additional_settings['category_id']);
          $file->setMilestoneId($additional_settings['milestone_id']);
          
          
          //attach files from mail to task
          $file_path = INCOMING_MAIL_ATTACHMENTS_FOLDER.'/'.$attachment->getTemporaryFilename();
          
          copy($file_path, UPLOAD_PATH . "/" . $attachment->getTemporaryFilename());
          $file->setMd5(md5_file(UPLOAD_PATH . "/" . $attachment->getTemporaryFilename()));
          
          $file->save();
          
          //@unlink($file_path);
          
           //notify sender and subscribe him
          $notify_sender = $additional_settings['notify_sender'];
          if($notify_sender) {
            $subscribe_users[] = $sender;
            $additional = array(
            	'exclude' => $sender
            );

            AngieApplication::notifications()
              ->notifyAbout(EMAIL_FRAMEWORK_INJECT_INTO . '/notify_email_sender', $file)
              ->sendToUsers($sender);
          }//if
      
          $file->subscriptions()->set($subscribe_users, true);
          
          if($file->subscriptions()->hasSubscribers()) {
            AngieApplication::notifications()
              ->notifyAbout('files/new_file', $file, $sender)
              ->sendToSubscribers();
          }//if
        
        }//foreach
      } else {
        throw new Error(IncomingMessageImportErrorActivityLog::ERROR_IMPORTING_FILE_DNX_EXIST);
      }//if
    
      return $file;
  
      //=========== end ================================//
    }//doActions
  
  } //IncomingMailFileAction
