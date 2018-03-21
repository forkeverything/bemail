<?php

namespace Tests\Unit\Translation;

use App\Translation\Message;
use App\Translation\Order;
use App\Translation\Order\OrderStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var Message
     */
    protected $message;
    /**
     * @var Order
     */
    protected $order;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->message = factory(Message::class)->create();
        $this->order = factory(Order::class)->create();
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'id' => 898989,
            'unit_count' => 10,
            'unit_price' => 3,
            'message_id' => $this->message->id,
            'order_status_id' => OrderStatus::available()->id,
        ];

        $order = Order::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($order->{$key}, $value);
        }

    }

    /**
     * @test
     */
    public function it_gets_the_message_being_translated()
    {
        $this->assertInstanceOf(Message::class, $this->order->message);
    }

    /**
     * @test
     */
    public function it_gets_the_order_status()
    {
        $this->assertInstanceOf(OrderStatus::class, $this->order->status);
    }

    /**
     * @test
     */
    public function it_instantiates_an_order_for_a_message()
    {
        $order = Order::newForMessage($this->message);
        $this->assertEquals($order->message_id, $this->message->id);
    }

    /**
     * @test
     */
    public function it_gets_the_order_id()
    {
        $this->assertTrue(is_int($this->order->id()));
    }

    /**
     * @test
     */
    public function it_sets_the_order_id()
    {
        $id = 989898;
        $this->order->id($id);
        $this->assertEquals($id, $this->order->id);
    }

    /**
     * @test
     */
    public function it_gets_the_unit_count()
    {
        $this->assertTrue(is_int($this->order->unitCount()));
    }

    /**
     * @test
     */
    public function it_sets_the_unit_count()
    {
        $unitCount = 20000;
        $this->order->unitCount($unitCount);
        $this->assertEquals($unitCount, $this->order->unit_count);
    }

    /**
     * @test
     */
    public function it_gets_the_unit_price()
    {
        $this->assertTrue(is_int($this->order->unitPrice()));
    }

    /**
     * @test
     */
    public function it_sets_the_unit_price()
    {
        $unitPrice = 500;
        $this->order->unitPrice($unitPrice);
        $this->assertEquals($unitPrice, $this->order->unit_price);
    }

    /**
     * @test
     */
    public function it_updates_the_order_status()
    {
        $order = factory(Order::class)->create([
            'order_status_id' => OrderStatus::available()->id
        ]);
        $this->assertEquals(OrderStatus::available(), $order->status);
        $order->updateStatus(OrderStatus::cancelled());
        $this->assertEquals(OrderStatus::cancelled(), $order->fresh()->status);
    }

}
