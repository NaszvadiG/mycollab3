<?php

  /**
   * object_assignee helper implementation
   * 
   * @package angie.frameworks.assignees
   * @subpackage helpers
   */

  /**
   * Display object assignee name
   *
   * @param array $params
   * @param Smarty $smarty
   */
  function smarty_function_object_assignee($params, &$smarty) {
    $object = array_var($params, 'object', null, true);
    if(!($object instanceof IAssignees)) {
      throw new InvalidInstanceError('object', $object, 'IAssignees');
    } // if
    
    if($object->assignees()->hasAssignee()) {
      if(isset($params['class'])) {
        $params['class'] .= ' object_assignee';
      } else {
        $params['class'] = 'object_assignee';
      } // if
      
      return open_html_tag('span', $params) . $object->assignees()->getAssignee()->getDisplayName(array_var($params, 'short', true)) . '</span>';
    } // if
    
    return '';
  } // smarty_function_object_assignee

?>