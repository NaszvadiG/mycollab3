<?php

  // Build on top of framework level controller
  AngieApplication::useController('fw_calendar_events', CALENDARS_FRAMEWORK);

  /**
   * Calendar events controller
   *
   * @package activeCollab.modules.system
   * @subpackage controllers
   */
  class CalendarEventsController extends FwCalendarEventsController {

  }