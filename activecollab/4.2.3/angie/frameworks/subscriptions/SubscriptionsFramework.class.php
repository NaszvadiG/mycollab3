<?php

  /**
   * Subscriptions framework definition
   *
   * @package angie.frameworks.subscriptions
   */
  class SubscriptionsFramework extends AngieFramework {
    
    /**
     * Short framework name
     *
     * @var string
     */
    protected $name = 'subscriptions';
    
    /**
     * Define routes for this framework
     */
    function defineRoutes() {
      Router::map('public_subscriptions_unsubscribe', 'public/subscriptions/unsubscribe', array('controller' => 'public_subscriptions', 'action' => 'unsubscribe', 'module' => SUBSCRIPTIONS_FRAMEWORK_INJECT_INTO));
    } // defineRoutes
    
    /**
     * Define subscription routes for given context
     *
     * @param string $context
     * @param string $context_path
     * @param array $context_defaults
     * @param array $context_requirements
     */
    function defineSubscriptionRoutesFor($context, $context_path, $controller_name, $module_name, $context_requirements = null) {
      Router::map("{$context}_subscriptions", "$context_path/subscriptions", array('controller' => $controller_name, 'action' => "{$context}_manage_subscriptions", 'module' => $module_name), $context_requirements);
      Router::map("{$context}_subscribe", "$context_path/subscribe", array('controller' => $controller_name, 'action' => "{$context}_subscribe", 'module' => $module_name), $context_requirements);
      Router::map("{$context}_unsubscribe", "$context_path/unsubscribe", array('controller' => $controller_name, 'action' => "{$context}_unsubscribe", 'module' => $module_name), $context_requirements);
      Router::map("{$context}_unsubscribe_all", "$context_path/unsubscribe_all", array('controller' => $controller_name, 'action' => "{$context}_unsubscribe_all", 'module' => $module_name), $context_requirements);
    } // defineSubscriptionRoutesFor
    
  }