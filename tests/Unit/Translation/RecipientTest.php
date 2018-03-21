<?php

namespace Tests\Unit;

use App\Translation\Message;
use App\Translation\Recipient;
use App\Translation\Recipient\RecipientType;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RecipientTest extends TestCase
{

    use DatabaseTransactions;

    private static $recipient;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp();
        static::$recipient = factory(Recipient::class)->create();
    }

    /**
     * @test
     */
    public function it_should_be_able_to_mass_assign_these_fields()
    {
        $fields = [
            'email' => 'sam@recipient.com',
            'recipient_type_id' => RecipientType::standard()->id,
            'message_id' => factory(Message::class)->create()->id
        ];

        $recipient = Recipient::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($recipient->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_recipient_type()
    {
        $recipient = factory(Recipient::class)->create([
            'recipient_type_id' => RecipientType::bcc()->id
        ]);
        $this->assertEquals(RecipientType::bcc()->id, $recipient->fresh()->type->id);
    }

    /**
     * @test
     */
    public function it_fetches_the_message_sent_to_recipient()
    {
        $message = factory(Message::class)->create();
        $recipient = factory(Recipient::class)->create(['message_id' => $message->id]);
        $this->assertEquals($recipient->message->id, $message->id);
    }

}
