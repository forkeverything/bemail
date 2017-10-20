<?php

namespace Tests\Unit;

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\MessageReceipt;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditTransactionTest extends TestCase
{
    use DatabaseTransactions;

    private static $transaction;

    public function setUp()
    {
        parent::setUp();
        static::$transaction = factory(CreditTransaction::class)->create();
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'amount' => 80,
            'credit_transaction_type_id' => CreditTransactionType::payment()->id,
            'message_receipt_id' => factory(MessageReceipt::class)->create()->id
        ];
        $transaction = CreditTransaction::create($fields);
        foreach ($fields as $key => $value) {
            $this->assertEquals($transaction->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_transaction_type()
    {
        $this->assertInstanceOf('App\Payments\CreditTransactionType', static::$transaction->type);
    }
    
    /**
     * @test
     */
    public function it_fetches_the_message_receipt()
    {
        $this->assertInstanceOf('App\Payments\MessageReceipt', static::$transaction->messageReceipt);
    }
}
