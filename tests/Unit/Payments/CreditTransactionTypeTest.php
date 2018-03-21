<?php

namespace Tests\Unit;

use App\Payment\Credit\CreditTransaction;
use App\Payment\Credit\Transaction\CreditTransactionType;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditTransactionTypeTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_finds_the_invite_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::invite()->name, 'invite');
    }

    /**
     * @test
     */
    public function it_finds_the_host_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::host()->name, 'host');
    }

    /**
     * @test
     */
    public function it_finds_the_payment_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::payment()->name, 'payment');
    }

    /**
     * @test
     */
    public function it_finds_the_manual_type_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::manual()->name, 'manual');
    }
    
    /** @test */
    public function it_finds_credit_transactions_of_given_type()
    {
        $this->assertCount(0, CreditTransactionType::payment()->transactions);
        factory(CreditTransaction::class, 5)->create([
            'credit_transaction_type_id' => CreditTransactionType::payment()->id
        ]);
        $this->assertCount(5, CreditTransactionType::payment()->transactions);
    }
}
