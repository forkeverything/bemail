<?php

namespace Tests\Unit\Payments;

use App\Payments\Plan;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use InvalidArgumentException;
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
    public function it_determines_the_plan_for_given_user()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Plan::class, Plan::forUser($user));
    }

    /**
     * @test
     */
    public function it_accepts_valid_plan_names()
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
    public function it_does_not_accept_invalid_names()
    {
        new Plan('foobar');
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

}
