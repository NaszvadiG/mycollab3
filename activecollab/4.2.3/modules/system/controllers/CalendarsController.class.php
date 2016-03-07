<?php

  // Build on top of framework level controller
  AngieApplication::useController('fw_calendars', CALENDARS_FRAMEWORK);

  /**
   * Calendars controller
   *
   * @package activeCollab.modules.system
   * @subpackage controllers
   */
  class CalendarsController extends FwCalendarsController {

  }