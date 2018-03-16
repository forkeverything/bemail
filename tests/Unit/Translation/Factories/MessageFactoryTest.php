<?php

namespace Tests\Unit\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use App\Translation\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_makes_a_new_message()
    {

        $subject = 'Super important message.';
        $body = 'Please translate this.';
        $autoTranslateReply = 'on';
        $sendToSelf = null;
        $langSrc = Language::findByCode('en');
        $langTgt = Language::findByCode('ja');

        $request = new CreateMessageRequest();
        $request->subject = $subject;
        $request->body = $body;
        $request->auto_translate_reply = $autoTranslateReply;
        $request->send_to_self = $sendToSelf;
        $request->lang_src = $langSrc->code;
        $request->lang_tgt = $langTgt->code;
        $request->recipients = 'sam@bemail.io,john@bemail.io';

        $user = factory(User::class)->create();

        $message = $user->newMessage($request)->make();

        // Check Message Model fields are stored correctly
        $this->assertEquals($subject, $message->subject);
        $this->assertEquals($body, $message->body);
        $this->assertEquals(1, $message->auto_translate_reply);
        $this->assertEquals(0, $message->send_to_self);
        $this->assertEquals($langSrc->id, $message->lang_src_id);
        $this->assertEquals($langTgt->id, $message->lang_tgt_id);

        // Not a reply
        $this->assertNull($message->reply_id);

        // Check recipients
        $this->assertCount(2, $message->recipients);
        $this->assertNotNull($message->recipients()->where('email', 'sam@bemail.io')->first());
        $this->assertNotNull($message->recipients()->where('email', 'john@bemail.io')->first());
    }

    /**
     * @test
     */
    public function it_makes_a_message_from_a_reply()
    {
        $subject = 'important message';
        $body = 'some body';
        $recipients = [
            'standard' => [
                'john@example.com',
                'jane@example.com',
            ],
            'cc' => ['stan@example.com'],
            'bcc' => ['sarah@example.com']
        ];

        $reply = factory(Reply::class)->create();
        $message = $reply->createMessage($recipients, $subject, $body)->make();

        $this->assertEquals($reply->id, $message->reply_id);
        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($subject, $message->subject);
        $this->assertEquals($body, $message->body);
        // There should be 5 recipients because when a Message is a reply
        // we add the original sender in as recipient.
        $this->assertCount(5, $message->recipients);
    }

    // TODO ::: Test Message Attachments
}


