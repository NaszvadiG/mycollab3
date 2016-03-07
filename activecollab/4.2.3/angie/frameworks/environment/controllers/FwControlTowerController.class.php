<?php

  // Build on top of
  AngieApplication::useController('admin', ENVIRONMENT_FRAMEWORK_INJECT_INTO);

  /**
   * Framework level control tower controller
   *
   * @package angie.frameworks.environment
   * @subpackage controllers
   */
  class FwControlTowerController extends AdminController {

    /**
     * Execute before any action
     */
    function __before() {
      parent::__before();

      if (!$this->request->isAsyncCall()) {
        $this->response->badRequest();
      } // if
    } // __before

    /**
     * Popup action
     */
    function index() {
      $control_tower = new ControlTower($this->logged_user);
      $control_tower->load();
      $control_tower->loadBadgeValue();

      $this->response->assign('control_tower', $control_tower);
    } // index

    /**
     * Show control towser settings page
     */
    function settings() {
      $control_tower = new ControlTower($this->logged_user);
      $control_tower_settings = $control_tower->getSettings();

      $this->response->assign('control_tower_settings', $control_tower_settings);

      if($this->request->isSubmitted()) {
        try {
          DB::beginWork('Updating control tower settings @ ' . __CLASS__);

          foreach($control_tower_settings as $group => $settings) {
            foreach($settings as $setting_name => $setting) {
              ConfigOptions::setValue($setting_name, (boolean) $this->request->post($setting_name));
            } // foreach
          } // foreach

          DB::commit('Control tower settings updated @ ' . __CLASS__);

          $this->response->ok();
        } catch(Exception $e) {
          DB::rollback('Failed to update control tower settings @ ' . __CLASS__);
          $this->response->exception($e);
        } // if
      } // if
    } // settings

    /**
     * Empty cache
     */
    function empty_cache() {
      if($this->request->isSubmitted()) {
        Router::cleanUpCache(true);
        AngieApplication::cache()->clear();

        $this->response->ok();
      } else {
        $this->response->badRequest();
      } // if
    } // empty_cache

    /**
     * Delete compiled templates
     */
    function delete_compiled_templates() {
      if($this->request->isSubmitted()) {
        AngieApplication::clearCompiledScripts();

        $this->response->ok();
      } else {
        $this->response->badRequest();
      } // if
    } // delete_compiled_templates

    /**
     * Rebuild images
     */
    function rebuild_images() {
      if($this->request->isSubmitted()) {
        $protect_assets = defined('PROTECT_ASSETS_FOLDER') && PROTECT_ASSETS_FOLDER;

        if(empty($protect_assets)) {
          AngieApplication::rebuildAssets();
        } // if

        $this->response->ok();
      } else {
        $this->response->badRequest();
      } // if
    } // rebuild_images

    /**
     * Rebuild lozalization
     */
    function rebuild_localization() {
      if($this->request->isSubmitted()) {
        AngieApplication::rebuildLocalization();

        $this->response->ok();
      } else {
        $this->response->badRequest();
      } // if
    } // rebuild_localization

  }