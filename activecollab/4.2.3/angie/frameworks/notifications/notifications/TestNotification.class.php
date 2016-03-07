<?php

  /**
   * Test notification class
   *
   * @package angie.frameworks.notifications
   * @subpackage models
   */
  class TestNotification extends Notification {

    /**
     * Return message for a given user
     *
     * @param IUser $user
     * @return string
     */
    function getMessage(IUser $user) {
      return 'This is a test notification';
    } // getMessage

  }