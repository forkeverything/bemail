<?php

namespace Tests\Unit\Payments\Listeners;

use App\Payments\Listeners\ProcessMessagePayment;
use App\Translation\Contracts\Translator;
use App\Translation\Events\NewMessageRequestReceived;
use ReflectionClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;

class ProcessMessagePaymentTest extends TestCase
{
    // Mock translator
    // set user credits
    // set word count so that it's less than credits
    // determine charge amount
    // mock user charge
    // test that credit transaction is recorded.
}
