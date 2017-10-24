<?php

namespace Tests\Unit;

use App\Payments\CreditTransactionType;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreditTransactionTypeTest extends TestCase
{

    /**
     * @test
     */
    public function it_finds_the_invite_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::invite()->slug, 'invite');
    }

    /**
     * @test
     */
    public function it_finds_the_host_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::host()->slug, 'host');
    }

    /**
     * @test
     */
    public function it_finds_the_payment_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::payment()->slug, 'payment');
    }

    /**
     * @test
     */
    public function it_finds_the_manual_add_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::manualAdd()->slug, 'manual');
        $this->assertEquals(CreditTransactionType::manualAdd()->add, 1);
    }

    /**
     * @test
     */
    public function it_finds_the_manual_deduct_type_record_using_static_method()
    {
        $this->assertEquals(CreditTransactionType::manualDeduct()->slug, 'manual');
        $this->assertEquals(CreditTransactionType::manualDeduct()->add, 0);
    }
}