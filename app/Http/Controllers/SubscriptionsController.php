<?php

namespace App\Http\Controllers;

use App\Payments\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionsController extends Controller
{
    // handles requests that  modify
    // User subscriptions.

    public function putChangePlan($plan)
    {
        return Auth::user()->subscription()->swap($plan);
    }

    public function deleteCancelPlan()
    {
        return Auth::user()->subscription()->swap(Plan::FREE);
    }
}
