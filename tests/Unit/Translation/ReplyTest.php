<?php

namespace Tests\Unit\Translation;

use App\Translation\Message;
use App\Translation\Reply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'sender_email' => 'foo@bar.com',
            'sender_name' => 'Mr. Baz',
            'original_message_id' => factory(Message::class)->create()->id
        ];

        $reply = Reply::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($reply->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_fetches_attached_message()
    {
        $reply = factory(Reply::class)->create();
        $message = factory(Message::class)->create([
            'reply_id' => $reply->id
        ]);
        $this->assertEquals($message->id, $reply->fresh()->attachedMessage->id);
    }

    /**
     *
     * @test
     */
    public function it_fetches_original_message()
    {
        $reply = factory(Reply::class)->create();
        $this->assertInstanceOf(Message::class, $reply->originalMessage);
    }

    /**
     * @test
     */
    public function it_creates_a_new_message()
    {
        $recipients = [
            'standard' => [
                'john@test.bemail.io'
            ],
            'cc' => [],
            'bcc' => []
        ];
        $subject = 'Important Message';
        $body = 'This is the message body';

        $reply = factory(Reply::class)->create();
        $message = $reply->createMessage($recipients, $subject, $body)->make();

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($subject, $message->subject);
    }

}
