<?php

  /**
   * on_rebuild_all_indices event handler implementation
   *
   * @package angie.frameworks.activity_logs
   * @subpackage handlers
   */

  /**
   * Handle on_rebuild_all_indices event
   *
   * @param array $steps
   */
  function tasks_handle_on_rebuild_all_indices(&$steps) {
    $steps[Router::assemble('tasks_admin_do_resolve_duplicate_id')] = lang('Task IDs');
  } // tasks_handle_on_rebuild_all_indices