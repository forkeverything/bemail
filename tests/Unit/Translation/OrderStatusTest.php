<?php

namespace Tests\Unit\Translation;

use App\Translation\Order\OrderStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStatusTest extends TestCase
{
    /**
     * Use static methods to fetch the right
     * record for a specific status.
     *
     * @test
     */
    public function it_fetches_the_right_concrete_record()
    {
        $statuses = [
            'available',
            'pending',
            'complete',
            'cancelled',
            'error'
        ];
        foreach($statuses as $status) {
            $this->assertEquals(OrderStatus::$status()->description, $status);
        }
    }
}
