<?php

  /**
   * Framework level labels manager implementation
   *
   * @package angie.frameworks.labels
   * @subpackage models
   */
  abstract class FwLabels extends BaseLabels {
    
    /**
     * Returns true if $user can define new labels
     * 
     * @param IUser $user
     * @return boolean
     */
    static function canAdd(IUser $user) {
      return $user instanceof User && $user->isAdministrator();
    } // canAdd
    
    /**
     * Cached array of label names
     *
     * @var array
     */
    static private $label_names = array();
    
    /**
     * Return label name
     * 
     * @param integer $label_id
     * @param mixed $default
     * @return string
     */
    static function getLabelName($label_id, $default = null) {
      if($label_id) {
        if(!array_key_exists($label_id, self::$label_names)) {
          self::$label_names = self::getIdNameMap();
        } // if
        
        return array_key_exists($label_id, self::$label_names) ? self::$label_names[$label_id] : $default;
      } // if
      
      return $default;
    } // getLabelName
    
    /**
     * Return label ID-s by list of label names
     * 
     * @param array $names
     * @param string $type
     * @return array
     */
    static function getIdsByNames($names, $type) {
      if($names) {
        $ids = DB::executeFirstColumn('SELECT id FROM ' . TABLE_PREFIX . 'labels WHERE name IN (?) AND type = ?', $names, $type);
        
        if($ids) {
          foreach($ids as $k => $v) {
            $ids[$k] = (integer) $v;
          } // foreach
        } // if
        
        return $ids;
      } else {
        return null;
      } // if
    } // getIdsByNames
    
    /**
     * Return labels by type name
     *
     * @param string $type
     * @param boolean $as_uppercase
     * @return array
     */
    static function findByType($type, $as_uppercase = true) {
      $cached_values_filename = "labels_type_".strtolower($type);
      $cached_values = AngieApplication::cache()->get($cached_values_filename);
      if ($cached_values) {
        return $cached_values;
      } // if

      $labels = array();
      $result = self::find(array(
        'conditions' => array('type = ?', $type), 
        'order' => 'name', 
      ));

      if ($result) {
        foreach($result as $row) {
          if ($as_uppercase) {
            $row->setName(strtoupper($row->getName()));
          } // if

          $labels[] = $row; // cache gets messed up if we save it as $result
        } // foreach
      } // if

      AngieApplication::cache()->set($cached_values_filename, $labels);
      return $labels;
    } // findByType
    
    /**
     * Return number of labels by type
     * 
     * @param string $type
     * @return integer
     */
    static function countByType($type) {
      return Labels::count(array('type = ?', $type));
    } // countByType
    
    /**
     * Array of cached default label (by label class)
     *
     * @var array
     */
    static private $default_labels = array();
  
    /**
     * Return default label by given type
     *
     * @param string $type
     * @param boolean $as_uppercase
     * @return Label
     */
    static function findDefault($type, $as_uppercase = true) {
      if(!array_key_exists($type, self::$default_labels)) {
        self::$default_labels[$type] = Labels::find(array(
          'conditions' => array('type = ? AND is_default', $type),
          'one' => true,
        ));
      } //if

      if ($as_uppercase && self::$default_labels[$type] instanceof Label) {
        self::$default_labels[$type]->setName(strtoupper(self::$default_labels[$type]->getName()));
      } // if
            
      return self::$default_labels[$type];
    } // findDefault
    
    /**
     * Set $label as default
     *
     * @param Label $label
     * @return Label
     * @throws Exception
     */
    static function setDefault(Label $label) {
      if($label->getIsDefault()) {
        return true;
      } // if
      
      $type = get_class($label);
      
      try {
        DB::beginWork('Setting default label @ ' . __CLASS__);
      
        $label->setIsDefault(true);
        $label->save();
        
        DB::execute('UPDATE ' . TABLE_PREFIX . 'labels SET is_default = ? WHERE id != ? AND type = ?', false, $label->getId(), $type);
        
        DB::commit('Default label set @ ' . __CLASS__);
        
        self::$default_labels[$type] = $label;
      } catch(Exception $e) {
        DB::rollback('Failed to set default label @ ' . __CLASS__);
        throw $e;
      } // try
      
      return self::$default_labels[$type];
    } // setDefault
    
    /**
     * Unset default label for given type
     * 
     * @param Label $label
     * @return mixed
     * @throws Exception
     */
    static function unsetDefault(Label $label) {
      if(!$label->getIsDefault()) {
        return true;
      } // if
      
      $type = get_class($label);
      
      try {
        DB::beginWork('Unsetting default label @ ' . __CLASS__);
      
        $label->setIsDefault(false);
        $label->save();
        
        DB::execute('UPDATE ' . TABLE_PREFIX . 'labels SET is_default = ? WHERE id != ? AND type = ?', false, $label->getId(), $type);
        
        DB::commit('Default label unset @ ' . __CLASS__);
        
        self::$default_labels[$type] = null;
      } catch(Exception $e) {
        DB::rollback('Failed to unset default label @ ' . __CLASS__);
        throw $e;
      } // try
      
      return self::$default_labels[$type];
    } // unsetDefault
    
    /**
     * Return types slice based on given criteria
     * 
     * @param integer $num
     * @param string $type
     * @param array $exclude
     * @param integer $timestamp
     * @param boolean $as_uppercase
     * @return DBResult
     */
    static function getSliceByType($num = 10, $type = 'Label', $exclude = null, $timestamp = null, $as_uppercase = true) {
      if($exclude) {
        $labels = Labels::find(array(
          'conditions' => array('type = ? AND id NOT IN (?)', $type, $exclude), 
          'order' => 'name', 
          'limit' => $num,  
        ));
      } else {
        $labels = Labels::find(array(
          'conditions' => array('type = ?', $type),
          'order' => 'name', 
          'limit' => $num,  
        ));
      } // if

      $labels_prepared = array();
      if ($as_uppercase && is_foreachable($labels)) {
        foreach ($labels as $key => $label) {
          $label->setName(strtoupper($label->getName()));
          $labels_prepared[$key] = $label;
        } // foreach
      } // if

      return $as_uppercase ? $labels_prepared : $labels;
    } // getSliceByType
    
    /**
     * Return ID - name map for a given label type
     *
     * @param string $type
     * @param boolean $as_uppercase
     * @return array
     */
    static function getIdNameMap($type = null, $as_uppercase = true) {
      $cache_key = array('models', 'labels', 'id_name_map');

      if($type) {
        $cache_key[] = $type;
      } // if

      $cached_values = AngieApplication::cache()->get($cache_key);
      if ($cached_values) {
        return $cached_values;
      } // if

      if (!is_null($type)) {
        $rows = DB::execute('SELECT id, name FROM ' . TABLE_PREFIX . 'labels WHERE type = ? ORDER BY name', $type);
      } else {
        $rows = DB::execute('SELECT id, name FROM ' . TABLE_PREFIX . 'labels ORDER BY name');
      } // if
      
      if(is_foreachable($rows)) {
        $result = array();
        
        foreach($rows as $row) {
          $result[(integer) $row['id']] = $as_uppercase ? strtoupper($row['name']) : $row['name'];
        } // foreach

        AngieApplication::cache()->set($cache_key, $result);
        return $result;
      } // if
      
      return null;
    } // getIdNameMap
    
    /**
     * Return Id - details map for given label type
     * 
     * @param string $type
     * @param boolean $always_uppercase
     * @return array
     */
    static function getIdDetailsMap($type, $always_uppercase = true) {
      $cached_values_filename = "labels_details_map_".strtolower($type);
      $cached_values = AngieApplication::cache()->get($cached_values_filename);
      if ($cached_values) {
        return $cached_values;
      } // if
      
      $rows = DB::execute('SELECT id, name, raw_additional_properties FROM ' . TABLE_PREFIX . 'labels WHERE type = ? ORDER BY name', $type);
      
      if (!is_foreachable($rows)) {
        return null;
      } // if
      
      $result = array();
        
      foreach($rows as $row) {
        $raw_properties = unserialize($row['raw_additional_properties']);
        $result[(integer) $row['id']] = array(
          'name' => $row['name'],
          'fg_color' => $raw_properties['fg_color'], 
          'bg_color' => $raw_properties['bg_color'],
          'always_uppercase' => $always_uppercase
        );;
      } // foreach
        
      AngieApplication::cache()->set($cached_values_filename, $result);
      return $result;
    } // getIdDetailsMap
    
  }