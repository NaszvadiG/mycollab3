<?php

  /**
   * Data Source framework on_status_bar event handler
   *
   * @package angie.frameworks.data_sources
   * @subpackage handlers
   */
  
  /**
   * Register status bar items
   *
   * @param StatusBar $status_bar
   * @param IUser $user
   */
  function data_sources_handle_on_status_bar(StatusBar &$status_bar, IUser &$user) {

    if($user->isAdministrator()) {
      $status_bar->add('data_sources', lang('Import from external source'), Router::assemble('data_source_popup'), AngieApplication::getImageUrl('status-bar/importer.png', DATA_SOURCES_FRAMEWORK), array(
        'group' => StatusBar::GROUP_LEFT,
        'autoClose' => 'true'
      ));
    } //if

  } // data_sources_handle_on_status_bar