<?php

namespace Tests\Unit\Payments;

use App\Payments\Plan;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;
use Laravel\Cashier\Subscription;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlanTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var array
     */
    protected $plans;

    public function setUp()
    {
        parent::setUp();

        $this->plans  = [
            new Plan(Plan::FREE),
            new Plan(Plan::REGULAR),
            new Plan(Plan::PROFESSIONAL),
        ];
    }

    /**
     * @test
     */
    public function it_instiates_with_valid_plan_names()
    {
        $names = ['free', 'regular', 'professional'];
        foreach ($names as $name) {
            $this->assertInstanceOf(Plan::class, new Plan($name));
        }
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_does_not_instantiate_with_invalid_plan_names()
    {
        new Plan('foobar');
    }

    /** @test */
    public function it_gets_free_plan()
    {
        $this->assertEquals(Plan::FREE, plan::free()->name());
    }

    /** @test */
    public function it_gets_regular_plan()
    {
        $this->assertEquals(Plan::REGULAR, plan::regular()->name());
    }

    /** @test */
    public function it_gets_professional_plan()
    {
        $this->assertEquals(Plan::PROFESSIONAL, plan::professional()->name());
    }

    /** @test */
    public function it_instantiates_for_user()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Plan::class, Plan::forUser($user));
    }

    /**
     * @test
     */
    public function it_determines_free_plan_when_user_is_not_subscribed()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Plan::class, Plan::forUser($user));
    }

    /** @test */
    public function it_gets_the_correct_plan_names()
    {
        $user = factory(User::class)->create();
        $plans = [
            'free',
            'regular',
            'professional'
        ];
        foreach ($plans as $plan) {
            if($existingSubscription = $user->subscriptions()->first()) $existingSubscription->delete();
            factory(Subscription::class)->create([
                'stripe_plan' => $plan,
                'user_id' => $user->id
            ]);
            $this->assertEquals($plan, $user->fresh()->plan()->name());
        }
    }

    /**
     * @test
     */
    public function it_gets_the_name()
    {
        foreach ($this->plans as $plan) {
            $this->assertTrue(is_string($plan->name()));
        }
    }

    /**
     * @test
     */
    public function it_gets_the_cost()
    {
        foreach ($this->plans as $plan) {
            $this->assertTrue(is_int($plan->cost()));
        }
    }

    /**
     * @test
     */
    public function it_gets_the_surcharge()
    {
        foreach ($this->plans as $plan) {
            $this->assertTrue(is_int($plan->surcharge()));
        }
    }

    /** @test */
    public function it_gets_users_on_free_plan()
    {
        $existingUsers = Plan::free()->users()->count();
        $this->assertCount($existingUsers, Plan::free()->users());
        // Create 5 User(s) that aren't subscribed.
        factory(User::class, 5)->create();
        // And 3 with expired subscriptions.
        factory(Subscription::class, 3)->create([
            'stripe_plan' => Plan::REGULAR,
            'ends_at' => Carbon::now()->addMonth(-1)
        ]);
        // 5 unsubscribed + 3 expired = 8 total on free plan.
        $this->assertCount(8 + $existingUsers, Plan::free()->users());
    }
    
    /** @test */
    public function it_gets_users_on_regular_plan()
    {
        $existingUsers = Plan::regular()->users()->count();
        $this->assertCount($existingUsers, Plan::regular()->users());
        // 10 Active subscriptions.
        factory(Subscription::class, 10)->create([
            'stripe_plan' => Plan::REGULAR
        ]);
        // 4 Inactive subscriptions.
        factory(Subscription::class, 4)->create([
            'stripe_plan' => Plan::REGULAR,
            'ends_at' => Carbon::now()->addMonth(-1)
        ]);
        // Total should still be 10.
        $this->assertCount(10 + $existingUsers, Plan::regular()->users());
    }

    /** @test */
    public function it_gets_users_on_professional_plan()
    {
        $existingUsers = Plan::professional()->users()->count();
        $this->assertCount($existingUsers, Plan::professional()->users());
        // 2 current Users
        factory(Subscription::class, 2)->create([
            'stripe_plan' => Plan::PROFESSIONAL
        ]);
        // 2 expired Users
        factory(Subscription::class, 2)->create([
            'stripe_plan' => Plan::PROFESSIONAL,
            'ends_at' => Carbon::now()->addMonth(-1)
        ]);
        // Total should still be 10.
        $this->assertCount(2 + $existingUsers, Plan::professional()->users());
    }

}
