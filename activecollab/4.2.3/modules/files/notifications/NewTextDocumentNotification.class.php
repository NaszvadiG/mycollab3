<?php

  /**
   * New text document notification
   *
   * @package activeCollab.modules.files
   * @subpackage notifications
   */
  class NewTextDocumentNotification extends Notification {

    /**
     * Return notification message
     *
     * @param IUser $user
     * @return string
     */
    function getMessage(IUser $user) {
      return lang("Document ':object_name' has been posted", array(
        'object_name' => $this->getParent() instanceof TextDocument ? $this->getParent()->getName() : '',
      ), true, $user->getLanguage());
    } // getMessage

  }