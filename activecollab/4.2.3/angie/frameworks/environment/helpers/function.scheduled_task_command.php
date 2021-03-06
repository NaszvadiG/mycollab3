<?php

  /**
   * Scheduled task command implementation
   *
   * @package activeCollab.modules.system
   * @subpackage helpers
   */

  /**
   * Render scheduled tasks command
   *
   * @param array $params
   * @param Smarty $smarty
   * @return string
   */
  function smarty_function_scheduled_task_command($params, &$smarty) {
    if(defined('PROTECT_SCHEDULED_TASKS') && PROTECT_SCHEDULED_TASKS) {
      $sufix = ' ' . escapeshellarg(substr(APPLICATION_UNIQUE_KEY, 0, 5));
    } else {
      $sufix = '';
    } // if
    
    $task = array_var($params, 'task');
    if($task && in_array($task, array(SCHEDULED_TASK_FREQUENTLY, SCHEDULED_TASK_HOURLY, SCHEDULED_TASK_DAILY))) {
      return escapeshellarg(ENVIRONMENT_PATH . "/tasks/{$task}.php") . $sufix;
    } else {
      return '';
    } // if
  } // smarty_function_scheduled_task_command