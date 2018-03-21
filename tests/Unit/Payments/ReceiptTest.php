<?php

namespace Tests\Unit\Payments;

use App\Payment\Credit\CreditTransaction;
use App\Payment\Credit\Transaction\CreditTransactionType;
use App\Payment\Plan;
use App\Payment\Receipt;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReceiptTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'plan' => Plan::FREE,
            'amount_charged' => 150,
            'reversed' => 1,
            'message_id' => factory(Message::class)->create()->id
        ];

        $receipt = Receipt::create($fields);

        foreach($fields as $key => $value) {
            $this->assertEquals($receipt->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_eager_loads_a_credit_transaction()
    {
        $receipt = factory(Receipt::class)->create();
        factory(CreditTransaction::class)->create([
            'credit_transaction_type_id' => CreditTransactionType::payment()->id,
            'receipt_id' => $receipt->id
        ]);
        $this->assertTrue($receipt->fresh()->relationLoaded('creditTransaction'));
    }

    /**
     * @test
     */
    public function it_fetches_the_message_that_its_for()
    {
        $message = factory(Message::class)->create();
        $receipt = factory(Receipt::class)->create([
            'message_id' => $message->id
        ]);
        $this->assertEquals($receipt->message->id, $message->id);
    }

    /**
     * @test
     */
    public function it_fetches_the_credit_transaction_for_given_receipt()
    {
        $receipt = factory(Receipt::class)->create();
        $transaction = factory(CreditTransaction::class)->create([
            'credit_transaction_type_id' => CreditTransactionType::payment()->id,
            'receipt_id' => $receipt->id
        ]);
        $this->assertEquals($receipt->creditTransaction->id, $transaction->id);
    }

    /**
     * @test
     */
    public function it_instantiates_for_given_message()
    {
        $message = factory(Message::class)->create();
        $this->assertInstanceOf(Receipt::class, Receipt::newForMessage($message));
    }

    /** @test */
    public function it_has_amount_charged()
    {
        $receipt = factory(Receipt::class)->create();
        $this->assertTrue(is_int($receipt->amountCharged()));
        $newAmount = 99999;
        $receipt->amountCharged($newAmount);
        $this->assertEquals($newAmount, $receipt->amountCharged());
    }
}
