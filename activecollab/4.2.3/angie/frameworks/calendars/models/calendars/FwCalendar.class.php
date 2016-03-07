<?php

  /**
   * Framework level calendar implementation
   *
   * @package angie.frameworks.calendars
   * @subpackage models
   */
  abstract class FwCalendar extends BaseCalendar implements IRoutingContext, IConfigContext, ICalendarEvents, IUsersContext, IState {

	  const DEFAULT_CALENDAR_GROUP = 'default';

	  // Default calendar mod ('weekly', 'monthly', 'yearly')
	  const DEFAULT_MODE = 'monthly';

    // Default calendar color
    const DEFAULT_COLOR = '8080e6';

	  // Share Types
	  const DONT_SHARE = 'dont_share';
	  const SHARE_WITH_EVERYONE = 'everyone';
	  const SHARE_WITH_MEMBERS_ONLY = 'members';
	  const SHARE_WITH_ADMINS_ONLY = 'admins';
	  const SHARE_WITH_SELECTED_USERS = 'selected';

	  // Filter Types
	  const FILTER_EVERYTHING_IN_ALL_PROJECTS = 'everything_in_all_projects';
	  const FILTER_EVERYTHING_IN_MY_PROJECTS = 'everything_in_my_projects';
	  const FILTER_MY_ASSIGNMENTS_IN_MY_PROJECTS = 'my_assignments_in_my_projects';
	  const FILTER_ONLY_MY_ASSIGNMENTS = 'only_my_assignments';
	  const FILTER_BY_USER = 'user';

	  /**
	   * Can view
	   *
	   * @param User $user
	   * @return bool
	   */
	  function canView(User $user) {
		  $creator = $this->getCreatedBy();
		  if ($creator instanceof User) {
			  switch ($this->getShareType()) {
				  case self::DONT_SHARE:
					  return $user->getId() == $creator->getId();
				  case self::SHARE_WITH_EVERYONE:
					  return true;
				  case self::SHARE_WITH_MEMBERS_ONLY:
					  return $user->isMember();
				  case self::SHARE_WITH_ADMINS_ONLY:
					  return $user->isAdministrator();
				  case self::SHARE_WITH_SELECTED_USERS:
					  return $this->users()->isMember($user);
			  } // switch
		  } // if

		  return false;
	  } // canView

	  /**
	   * Is user creator of this calendar
	   *
	   * @param User $user
	   * @return bool
	   */
	  function isCreator(User $user) {
		  return $user->getId() == $this->getCreatedById();
	  } // isCreator

	  /**
	   * Can edit
	   *
	   * @param User $user
	   * @return bool
	   */
	  function canEdit(User $user) {
		  return $this->isCreator($user);
	  } // canEdit

	  /**
	   * Can delete
	   *
	   * @param User $user
	   * @return bool
	   */
	  function canDelete(User $user) {
		  return $this->isCreator($user);
	  } // canDelete

    /**
     * Return routing context name
     *
     * @return string
     */
    function getRoutingContext() {
      return 'calendar';
    } // getRoutingContext

    /**
     * Return routing context parameters
     *
     * @return mixed
     */
    function getRoutingContextParams() {
      return array('calendar_id' => $this->getId());
    } // getRoutingContextParams

	  function getColor() {
		  $config = Calendars::getLoggedUserConfigByTypeId($this->getType(), $this->getId());
		  return array_var($config, 'color', Calendar::DEFAULT_COLOR);
	  } // getColor

	  function setColor($value) {
		  $config = array(
			  'color' => $value
		  );
		  Calendars::setConfigForLoggedUserByTypeId($this->getType(), $this->getId(), $config);
	  } // setColor

	  function isVisible() {
		  $config = Calendars::getLoggedUserConfigByTypeId($this->getType(), $this->getId());
		  return array_var($config, 'visible', 1);
	  } // isVisible

	  function setVisible($value = true) {
		  $config = array(
			  'visible' => $value ? 1 : 0
		  );
		  Calendars::setConfigForLoggedUserByTypeId($this->getType(), $this->getId(), $config);
	  } // setVisible

	  function describe(IUser $user, $detailed = false, $for_interface = false) {
		  $result = parent::describe($user, $detailed, $for_interface);

		  $result['color']            = $this->getColor();
		  $result['visible']          = $this->isVisible();
		  $result['created_by_id']    = $this->getCreatedById();
		  $result['created_by_name']  = $this->getCreatedByName();
		  $result['permissions']      = array(
			  'can_edit'    => true,
			  'can_trash'   => $this->isCreatedBy($user)
		  );
		  if ($this->isCreatedBy($user)) {
			  $result['urls']             = array(
				  'edit'    => $this->getEditUrl(),
				  'trash'   => $this->state()->getTrashUrl(),
				  'change_visibility' => Router::assemble('calendar_change_visibility', array('calendar_id' => $this->getId()))
			  );
		  } else {
			  $result['urls']             = array(
				  'edit'              => Router::assemble('calendar_change_color', array('calendar_id' => $this->getId())),
				  'change_visibility' => Router::assemble('calendar_change_visibility', array('calendar_id' => $this->getId())),
			  );
		  } // if

		  $result['urls']['ical'] = Router::assemble('calendar_ical_subscribe', array('calendar_id' => $this->getId()));

		  return $result;
	  } // describe

	  // ---------------------------------------------------
	  //  Implementations
	  // ---------------------------------------------------

	  /**
	   * Users helper instance
	   *
	   * @var ICalendarUsersContextImplementation
	   */
	  private $users = false;

	  /**
	   * Return users helper implementation
	   *
	   * @return ICalendarUsersContextImplementation
	   */
	  function users() {
		  if($this->users === false) {
			  $this->users = new ICalendarUsersContextImplementation($this);
		  } // if

		  return $this->users;
	  } // users

	  /**
	   * Cached state helper instance
	   *
	   * @var IProjectStateImplementation
	   */
	  private $state = false;

	  /**
	   * Return state helper instance
	   *
	   * @return ICalendarStateImplementation
	   */
	  function state() {
		  if($this->state === false) {
			  $this->state = new ICalendarStateImplementation($this);
		  } // if

		  return $this->state;
	  } // state

    /**
     * Cached calendar event helper
     *
     * @var ICalendarEventsImplementation
     */
    private $calendar_events = false;

    /**
     * Return calendar events helper instance
     *
     * @return ICalendarEventsImplementation
     */
    function calendarEvents() {
      if($this->calendar_events === false) {
        $this->calendar_events = new ICalendarEventsImplementation($this);
      } // if

      return $this->calendar_events;
    } // calendarEvents

    // ---------------------------------------------------
    //  System
    // ---------------------------------------------------

		/**
		 * @return bool|void
		 */
		function delete() {
			parent::delete();
		} // delete

    /**
     * Validate before save
     *
     * @param ValidationErrors $errors
     */
    function validate(ValidationErrors &$errors) {
      if (!$this->validatePresenceOf('name')) {
        $errors->addError(lang('Calendar name is required'), 'name');
      } // if

	    parent::validate($errors);
    } // validate

  }