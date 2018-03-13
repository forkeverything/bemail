<?php

namespace App\Http\Controllers;

use App\Payments\Plan;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Subscription;

class SubscriptionController extends Controller
{

    public function getSubscriptionsPage()
    {
        return view('subscriptions');
    }

    /**
     * Sign up for a new subscription.
     *
     * @param Request $request
     * @param $plan
     * @return Subscription
     */
    public function postNewSubscription(Request $request, $plan)
    {
        $user = Auth::user();
        return $user->newSubscription('default', $plan)->create($request->stripe_cc_token);
    }

    /**
     * Change subscription plan.
     *
     * @param $plan
     * @return Subscription
     */
    public function putChangePlan($plan)
    {
        $user = Auth::user();
        return $user->subscription()->swap($plan);
    }

    /**
     * Save updated credit card token.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function putUpdateCard(Request $request)
    {
        $user = Auth::user();
        $user->updateCard($request->stripe_cc_token);
        return response("Updated credit card details.", 200);
    }

    /**
     * Cancel user subscription at end of billing cycle.
     *
     * @return \Laravel\Cashier\Subscription|null
     */
    public function deleteCancelPlan()
    {
        $user = Auth::user();
        if ($user->subscribed()) {
            $user->subscription()->cancel();
        }
        return $user->subscription();
    }

    /**
     * Resumes a previously cancelled subscription.
     *
     * @return Subscription
     */
    public function putResumePlan()
    {
        $user = Auth::user();
        return $user->subscription('main')->resume();
    }


}
