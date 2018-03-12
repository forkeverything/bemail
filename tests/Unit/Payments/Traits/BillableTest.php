<?php

namespace Tests\Unit\Payments\Traits;

use App\Payments\Plan;
use App\Payments\Subscription;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BillableTest extends TestCase
{

    use DatabaseTransactions;

   /**
    * @test
    */
   public function it_gets_the_right_subscription()
   {
       $user = factory(User::class)->create();
       $user->newSubscription(Subscription::MAIN, Plan::FREE)->create();
       $this->assertInstanceOf(Subscription::class, $user->subscription());
   }
}
