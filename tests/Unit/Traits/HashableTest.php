<?php

namespace Tests\Unit\Traits;

use App\Translation\Message;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HashableTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_finds_the_right_model_using_hash_id()
    {
        $message = factory(Message::class)->create();
        $hash = $message->hash;
        $this->assertEquals(Message::findByHash($hash)->id, $message->id);
    }

    /**
     * @test
     */
    public function it_retrieves_a_models_hash_id()
    {
        $this->assertNull(factory(User::class)->create()->hash);
        $this->assertNotNull(factory(Message::class)->create()->hash);
    }
}
