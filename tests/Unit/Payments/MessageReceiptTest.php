<?php

namespace Tests\Unit;

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\MessageReceipt;
use App\Payments\Plan;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageReceiptTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'plan' => Plan::FREE,
            'cost_per_word' => 7,
            'amount_charged' => 150,
            'message_id' => factory(Message::class)->create()->id
        ];

        $receipt = MessageReceipt::create($fields);

        foreach($fields as $key => $value) {
            $this->assertEquals($receipt->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_message_that_it_is_for()
    {
        $message = factory(Message::class)->create();
        $receipt = factory(MessageReceipt::class)->create([
            'message_id' => $message->id
        ]);
        $this->assertEquals($receipt->message->id, $message->id);
    }
    
    /**
     * @test
     */
    public function it_fetches_the_credit_transaction_for_given_receipt()
    {
        $receipt = factory(MessageReceipt::class)->create();
        $transaction = factory(CreditTransaction::class)->create([
            'credit_transaction_type_id' => CreditTransactionType::payment()->id,
            'message_receipt_id' => $receipt->id
        ]);
        $this->assertEquals($receipt->creditTransaction->id, $transaction->id);
    }

    /**
     * @test
     */
    public function it_makes_a_receipt()
    {
        $message = factory(Message::class)->create();
        $unitPrice = 0.05;
        $amount = 20;
        $receipt = MessageReceipt::makeFor($message, $unitPrice, $amount);
        $this->assertEquals($message->id, $receipt->message_id);
    }
}
