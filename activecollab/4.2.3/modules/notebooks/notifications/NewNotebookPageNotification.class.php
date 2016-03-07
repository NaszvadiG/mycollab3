<?php

  /**
   * New notebook page notification
   *
   * @package activeCollab.modules.notebooks
   * @subpackage notifications
   */
  class NewNotebookPageNotification extends Notification {

    /**
     * Return notification message
     *
     * @param IUser $user
     * @return string
     */
    function getMessage(IUser $user) {
      return lang("Page ':name' has been Created", array(
        'name' => $this->getParent() instanceof NotebookPage ? $this->getParent()->getName() : '',
      ), true, $user->getLanguage());
    } // getMessage

    /**
     * Return notebook instance
     *
     * @return Notebook
     */
    function getNotebook() {
      return DataObjectPool::get('Notebook', $this->getAdditionalProperty('notebook_id'));
    } // getNotebook

    /**
     * Set parent notebook instace
     *
     * @param Notebook $notebook
     * @return NewNotebookPageNotification
     */
    function &setNotebook(Notebook $notebook) {
      $this->setAdditionalProperty('notebook_id', $notebook->getId());

      return $this;
    } // setNotebook

    /**
     * Return additional template variables
     *
     * @param NotificationChannel $channel
     * @return array
     */
    function getAdditionalTemplateVars(NotificationChannel $channel) {
      return array(
        'notebook' => $this->getNotebook(),
      );
    } // getAdditionalTemplateVars

  }