<?php

namespace Tests\Unit\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Factories\MessageFactory;
use App\Translation\Message;
use App\Translation\Reply;
use App\Translation\TranslationStatus;
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
        $user = factory(User::class)->create();
        $sourceLanguage = Language::first();
        $targetLanguage = Language::find(2);

        $subject = 'Super important message.';
        $body = 'Please translate this.';
        $autoTranslateReply = 'on';
        $sendToSelf = null;     // checkbox unchecked
        $langSrcId = $sourceLanguage->id;
        $langTgtId = $targetLanguage->id;
        $recipientEmails = ['sam@bemail.io', 'john@bemail.io'];
        $attachments = [];

        $message = MessageFactory::new(
            $subject,
            $body,
            !! $autoTranslateReply,
            !! $sendToSelf,
            $langSrcId,
            $langTgtId,
            $recipientEmails,
            $attachments
        )->owner($user)->make();

        // Check Message Model fields are stored correctly
        $this->assertEquals($subject, $message->subject);
        $this->assertEquals($body, $message->body);
        $this->assertEquals(1, $message->auto_translate_reply);
        $this->assertEquals(0, $message->send_to_self);
        $this->assertEquals($sourceLanguage->id, $message->lang_src_id);
        $this->assertEquals($targetLanguage->id, $message->lang_tgt_id);
        $this->assertEquals(TranslationStatus::available()->id, $message->translation_status_id);

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
        $message = MessageFactory::reply($reply)
                                 ->recipientEmails($recipients)
                                 ->subject($subject)
                                 ->body($body)
                                 ->make();
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


