<?php

  /**
   * Update activeCollab 2.1 to activeCollab 2.1.1
   *
   * @package activeCollab.upgrade
   * @subpackage scripts
   */
  class Upgrade_0012 extends AngieApplicationUpgradeScript {
    
    /**
     * Initial system version
     *
     * @var string
     */
    protected $from_version = '2.1';
    
    /**
     * Final system version
     *
     * @var string
     */
    protected $to_version = '2.1.1';
    
    /**
     * Return script actions
     *
     * @param void
     * @return array
     */
    function getActions() {
    	return array(
    	  'updateExistingTables' => 'Update existing tables',
    	);
    } // getActions
    
    /**
     * Update existing tables
     *
     * @param void
     * @return boolean
     */
    function updateExistingTables() {
      $mailboxes_table = TABLE_PREFIX . 'incoming_mailboxes';
      
      if(in_array($mailboxes_table, DB::listTables(TABLE_PREFIX))) {
        DB::execute("alter table $mailboxes_table add column accept_all_registered tinyint(3) unsigned NOT NULL default 0 after enabled");
      } // if
      
      return true;
    } // updateExistingTables
    
  }