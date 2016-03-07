<?php
  /**
   * Class description
   *
   * @package
   * @subpackage
   */

  /**
   * Render my projects list
   *
   * @param array $params
   * @param Smarty $smarty
   * @return string
   */
  function smarty_function_my_projects($params, &$smarty) {
    $user = array_required_var($params, 'user', false, 'User');

    AngieApplication::useWidget('my_projects', SYSTEM_MODULE);
    AngieApplication::useHelper('favorite_object', FAVORITES_FRAMEWORK);

    $projects = Projects::findActiveByUser($user);

    if($projects) {
      $favorite_projects = $other_projects = '';

      foreach($projects as $project) {
        $project_row = '<tr project_id="' . $project->getId() . '">';

        $project_row .= '<td class="icon left" width="16px"><img src="' . clean($project->avatar()->getUrl(IProjectAvatarImplementation::SIZE_SMALL)) . '"></td>';
        $project_row .= '<td class="name">' . object_link($project, null, array('class' => 'quick_view_item')) . '</td>';

        if (($label = $project->label()->get()) instanceof Label) {
          $rendered_label = $label->render(true);
        } else {
          $rendered_label = '';
        } // if

        $project_row .= '<td class="project_options">' . $rendered_label . ProjectProgress::renderRoundProjectProgress($project) . '</td>';
        $project_row .= '<td class="favorite right" width="16px">' . smarty_function_favorite_object(array(
          'object' => $project,
          'user' => $user,
        ), SmartyForAngie::getInstance())  . '</td>';

        $project_row .= '</tr>';

        if(Favorites::isFavorite($project, $user)) {
          $favorite_projects .= $project_row;
        } else {
          $other_projects .= $project_row;
        } // if
      } // foreach

      return '<div class="my_projects"><table class="common" cellspacing="0"><tbody>' . $favorite_projects . $other_projects . '</tbody></table></div>';
    } else {
      return '<p class="center" style="color: #999;">' . lang('There are no active projects') . '</p>';
    } // if
  } // smarty_function_my_projects