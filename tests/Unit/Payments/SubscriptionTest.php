<?php

namespace Tests\Unit\Payments;

use App\Payments\Plan;
use App\Payments\Subscription;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_subscription_plan()
    {
        $user = factory(User::class)->create();
        $subscription = $user->newSubscription(Subscription::MAIN, Plan::FREE)->create();
        $this->assertInstanceOf(Plan::class, $subscription->plan());
    }
}
