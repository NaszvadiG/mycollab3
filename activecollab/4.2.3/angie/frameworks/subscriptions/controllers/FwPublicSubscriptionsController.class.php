<?php

  // Build on top of system module
  AngieApplication::useController('frontend', ENVIRONMENT_FRAMEWORK_INJECT_INTO);

  /**
   * Framework level public subscriptions controller
   *
   * @package angie.frameworks.subscriptions
   * @subpackage controllers
   */
  abstract class FwPublicSubscriptionsController extends FrontendController {
    
    /**
     * One click unsubscribe, no login required
     */
    function unsubscribe() {
      $code = $this->request->get('code');
      list($subscription_id, $subscription_code) = explode('-', $code);
      if (!$subscription_id || !$subscription_code) {
        $this->response->notFound();
      } // if

      $subscription = Subscriptions::findById($subscription_id);
      if (!($subscription instanceof Subscription)) {
        $this->response->notFound();
      } // if

      if ($subscription->getCode() != $subscription_code) {
        $this->response->notFound();
      } // if

      try {
        $this->response->assign(array(
          'active_object'     => $subscription->getParent(),
          'active_user_email' => $subscription->getUserEmail()
        ));

        $subscription->delete();
        if ($subscription->getUser() instanceof User) {
          AngieApplication::cache()->removeByObject($subscription->getUser(), 'subscriptions');
        } // if
      } catch(Exception $e) {
        $this->response->exception($e);
      } // try
    } // unsubscribe
  }