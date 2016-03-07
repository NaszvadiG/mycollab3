<?php

  /**
   * object_link helper
   *
   * @package activeCollab.modules.system
   * @subpackage helpers
   */
  
  /**
   * Render default object link
   *
   * @param array $params
   * @param Smarty $smarty
   * @return string
   */
  function smarty_function_object_link($params, &$smarty) {
    if(isset($params['object']) && $params['object'] instanceof ApplicationObject) {
      return object_link($params['object'], array_var($params, 'excerpt', null), array_var($params, 'additional', null), array_var($params, 'quick_view', null));
    } else {
      throw new InvalidInstanceError('object', $params['object'], 'ApplicationObject');
    } // if
  } // smarty_function_object_link