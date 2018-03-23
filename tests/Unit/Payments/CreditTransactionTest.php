<?php

namespace Tests\Unit;

use App\Payment\Credit\CreditTransaction;
use App\Payment\Credit\Transaction\CreditTransactionType;
use App\Payment\Receipt;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditTransactionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var CreditTransaction
     */
    private $transaction;

    public function setUp()
    {
        parent::setUp();
        $this->transaction = factory(CreditTransaction::class)->create();
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
            'receipt_id' => factory(Receipt::class)->create()->id
        ];
        $transaction = CreditTransaction::create($fields);
        foreach ($fields as $key => $value) {
            $this->assertEquals($transaction->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_user_whose_credit_was_adjusted()
    {
        $this->assertInstanceOf(User::class, $this->transaction->user);
    }

    /**
     * @test
     */
    public function it_fetches_the_message_receipt()
    {
        $this->assertInstanceOf(Receipt::class, $this->transaction->receipt);
    }

    /**
     * @test
     */
    public function it_fetches_the_transaction_type()
    {
        $this->assertInstanceOf(CreditTransactionType::class, $this->transaction->type);
    }

    /** @test */
    public function it_sets_credit_transaction_type()
    {
        $transaction = factory(CreditTransaction::class)->create([
            'credit_transaction_type_id' => CreditTransactionType::payment()->id
        ]);
        $transaction->type(CreditTransactionType::manual())->save();
        $this->assertEquals(CreditTransactionType::manual()->id, $transaction->fresh()->credit_transaction_type_id);
    }

    /** @test */
    public function it_instantiates_for_a_user()
    {
        $user = factory(User::class)->create();
        $creditTransaction = CreditTransaction::newForUser($user);
        $this->assertEquals($user->id, $creditTransaction->user_id);
    }

    /** @test */
    public function it_has_a_transaction_amount()
    {
        $this->assertTrue(is_int($this->transaction->amount()));
        $this->transaction->amount(800);
        $this->assertEquals(800, $this->transaction->amount());
    }

}
