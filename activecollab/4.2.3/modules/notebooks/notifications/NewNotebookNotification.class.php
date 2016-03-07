<?php

  /**
   * New notebook email notification
   *
   * @package activeCollab.modules.notebooks
   * @subpackage notifications
   */
  class NewNotebookNotification extends Notification {

    /**
     * Return notification message
     *
     * @param IUser $user
     * @return string
     */
    function getMessage(IUser $user) {
      return lang("Notebook ':name' has been Created", array(
        'name' => $this->getParent() instanceof Notebook ? $this->getParent()->getName() : '',
      ), true, $user->getLanguage());
    } // getMessage

  }