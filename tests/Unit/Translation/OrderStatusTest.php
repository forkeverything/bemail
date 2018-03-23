<?php

namespace Tests\Unit\Translation;

use App\Translation\Order\OrderStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderStatusTest extends TestCase
{

    private  $statuses = [
        'available',
        'pending',
        'complete',
        'cancelled',
        'error'
    ];

    /**
     * Use static methods to fetch the right
     * record for a specific status.
     *
     * @test
     */
    public function it_fetches_the_right_concrete_record()
    {
        foreach($this->statuses as $status) {
            $this->assertEquals(OrderStatus::$status()->description, $status);
        }
    }
    
    /** 
     * @test
     */
    public function it_checks_whether_order_has_status()
    {
        foreach($this->statuses as $statusName) {
            $status = OrderStatus::$statusName();
            $funcName = 'is' . ucfirst($statusName);

            $this->assertTrue(call_user_func([
                $status, $funcName
            ]));
        }
    }
}
