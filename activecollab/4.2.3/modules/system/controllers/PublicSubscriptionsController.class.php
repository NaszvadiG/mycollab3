<?php

  // Build on top of framework level controller
  AngieApplication::useController('fw_public_subscriptions', SUBSCRIPTIONS_FRAMEWORK);

  /**
   * Public subscriptions controller
   *
   * @package activeCollab.modules.system
   * @subpackage controllers
   */
  class PublicSubscriptionsController extends FwPublicSubscriptionsController {
    
  }