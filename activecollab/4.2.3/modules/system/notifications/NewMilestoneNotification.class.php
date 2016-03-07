<?php

  /**
   * New milestone email notification
   *
   * @package activeCollab.modules.system
   * @subpackage notifications
   */
  class NewMilestoneNotification extends Notification {

    /**
     * Return notification message
     *
     * @param IUser $user
     * @return string
     */
    function getMessage(IUser $user) {
      return lang("Milestone ':object_name' has been created", array(
        'object_name' => $this->getParent() instanceof Milestone ? $this->getParent()->getName() : '',
      ), true, $user->getLanguage());
    } // getMessage

  }