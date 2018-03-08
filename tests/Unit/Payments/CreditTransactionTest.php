<?php

namespace Tests\Unit;

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\MessageReceipt;
use App\User;
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
            'user_id' => factory(User::class)->create()->id,
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
    public function it_fetches_the_user_whose_credit_was_adjusted()
    {
        $this->assertInstanceOf('App\User', static::$transaction->user);
    }
    
    /**
     * @test
     */
    public function it_fetches_the_message_receipt()
    {
        $this->assertInstanceOf('App\Payments\MessageReceipt', static::$transaction->messageReceipt);
    }

    /**
     * @test
     */
    public function it_records_a_transaction()
    {
        $user = factory(User::class)->create();
        $type = CreditTransactionType::all()->random();
        $amount = 18;
        $transaction = CreditTransaction::record($user, $type, $amount);
        $this->assertEquals(18, $transaction->amount);
    }
}
