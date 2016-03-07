<?php

  // Build on top of framework level implementation
  AngieApplication::useController('fw_scheduled_tasks', ENVIRONMENT_FRAMEWORK);

  /**
   * Scheduled tasks controller
   *
   * @package activeCollab.modules.system
   * @subpackage controllers
   */
  class ScheduledTasksController extends FwScheduledTasksController {
    
  }