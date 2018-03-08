<?php

namespace Tests\Unit;

use App\Language;
use App\Payment\MessageReceipt;
use App\Translation\Attachment;
use App\Translation\Message;
use App\Translation\MessageError;
use App\Translation\Recipient;
use App\Translation\Reply;
use App\Translation\TranslationStatus;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var Message
     */
    private static $message;

    public function setUp()
    {
        parent::setUp();
        static::$message = factory(Message::class)->create([
            'reply_id' => factory(Reply::class)->create()->id
        ]);
    }

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'subject' => 'Super important message',
            'body' => 'Please translate this',
            'translated_body' => 'Some translated text',
            'auto_translate_reply' => 0,
            'send_to_self' => 1,
            'user_id' => factory(User::class)->create()->id,
            'reply_id' => factory(Reply::class)->create()->id,
            'translation_status_id' => TranslationStatus::available()->id,
            'lang_src_id' => 1,
            'lang_tgt_id' => 2,
            'gengo_order_id' => 888
        ];

        $message = Message::create($fields);

        foreach ($fields as $key => $value) {
            $this->assertEquals($message->{$key}, $value);
        }

    }

    /**
     * @test
     */
    public function it_appends_these_dynamic_properties()
    {
        $dynamicProperties = [
            'hash',
            'word_count',
            'has_recipients',
            'readable_created_at'
        ];
        foreach ($dynamicProperties as $property) {
            $this->assertContains($property, array_keys(static::$message->toArray()));
        }
    }

    /**
     * @test
     */
    public function it_retrieves_these_properties_as_carbon_dates()
    {
        $fields = [
            'created_at'
        ];
        foreach ($fields as $field) {
            $this->assertInstanceOf('Illuminate\Support\Carbon', static::$message->{$field});
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_message_owner()
    {
        $this->assertInstanceOf('App\User', static::$message->owner);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_the_message()
    {
        $this->assertCount(0, static::$message->recipients);
        factory(Recipient::class, 5)->create(['message_id' => static::$message->id]);
        $this->assertCount(5, static::$message->fresh()->recipients);
    }

    /**
     * @test
     */
    public function it_fetches_reply_that_message_is_intended_for()
    {
        $this->assertInstanceOf(Reply::class, static::$message->parentReplyClass);
    }

    /**
     * @test
     */
    public function it_fetches_replies_to_target_message()
    {
        $message = factory(Message::class)->create();
        $this->assertCount(0, $message->replies);
        $reply = factory(Reply::class, 5)->create(['original_message_id' => $message->id]);
        $this->assertCount(5, $message->fresh()->replies);
    }

    /**
     * @test
     */
    public function it_checks_whether_message_is_for_a_reply()
    {
        $this->assertTrue(static::$message->isReply());
    }

    /**
     * @test
     */
    public function it_gets_the_right_sender_email_for_original_messages()
    {
        $user = factory(User::class)->create();
        $originalMessage = factory(Message::class)->create([
            'user_id' => $user->id
        ]);
        $this->assertEquals($user->email, $originalMessage->senderEmail());
    }

    /**
     * @test
     */
    public function it_gets_the_right_sender_email_for_reply_messages()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();
        $originalMessage = factory(Message::class)->create([
            'user_id' => $user->id,
            'reply_id' => $reply->id
        ]);
        $this->assertEquals($reply->sender_email, $originalMessage->senderEmail());
    }

    /**
     * @test
     */
    public function it_gets_the_right_sender_name_for_original_messages()
    {
        $user = factory(User::class)->create();
        $originalMessage = factory(Message::class)->create([
            'user_id' => $user->id
        ]);
        $this->assertEquals($user->name, $originalMessage->senderName());
    }

    /**
     * @test
     */
    public function it_gets_the_right_sender_name_for_reply_messages()
    {
        $user = factory(User::class)->create();
        $reply = factory(Reply::class)->create();
        $originalMessage = factory(Message::class)->create([
            'user_id' => $user->id,
            'reply_id' => $reply->id
        ]);
        $this->assertEquals($reply->sender_name, $originalMessage->senderName());
    }

    /**
     * @test
     */
    public function it_checks_whether_message_has_recipients()
    {
        $this->assertFalse(static::$message->has_recipients);
        factory(Recipient::class, 1)->create(['message_id' => static::$message->id]);
        $this->assertTrue(static::$message->fresh()->has_recipients);
    }

    /**
     * @test
     */
    public function it_fetches_message_attachments()
    {
        $this->assertCount(0, static::$message->attachments);
        factory(Attachment::class, 3)->create([
            'message_id' => static::$message->id
        ]);
        $this->assertCount(3, static::$message->fresh()->attachments);
    }

    /**
     * @test
     */
    public function it_has_a_translation_status()
    {
        $this->assertInstanceOf('App\Translation\TranslationStatus', static::$message->status);
    }

    /**
     * @test
     */
    public function it_has_a_source_language()
    {
        $this->assertInstanceOf('App\Language', static::$message->sourceLanguage);
    }

    /**
     * @test
     */
    public function it_has_a_target_language()
    {
        $this->assertInstanceOf('App\Language', static::$message->targetLanguage);
    }

    /**
     * @test
     */
    public function it_fetches_a_receipt()
    {
        $this->assertNull(static::$message->receipt);
        $receipt = factory(MessageReceipt::class)->create([
            'message_id' => static::$message
        ]);
        $this->assertEquals($receipt->id, static::$message->fresh()->receipt->id);
    }

    /**
     * @test
     */
    public function it_gets_the_right_word_count()
    {
        $this->assertEquals(str_word_count(static::$message->body), static::$message->word_count);
    }

    /**
     * @test
     */
    public function it_checks_whether_there_are_recipients()
    {
        $this->assertFalse(static::$message->has_recipients);
        factory(Recipient::class)->create(['message_id' => static::$message->id]);
        $this->assertTrue(static::$message->fresh()->has_recipients);
    }
    
    /**
     * @test
     */
    public function it_fetches_message_error()
    {
        $this->assertNull(static::$message->error);
        factory(MessageError::class)->create([
            'message_id' => static::$message->id
        ]);
        $this->assertNotNull(static::$message->fresh()->error);
    }

    /**
     *
     * @test
     */
    public function it_updates_message_translation_status()
    {
        $translationStatuses = TranslationStatus::all();
        foreach ($translationStatuses as $status) {
            static::$message->updateStatus($status);
            $this->assertEquals(static::$message->translation_status_id, $status->id);
        }
    }

    /**
     * @test
     */
    public function it_updates_gengo_order_id()
    {
        static::$message->update([
            'gengo_order_id' => 999,
        ]);
        $this->assertEquals(999, static::$message->gengoOrderId());
        static::$message->gengoOrderId(123);
        $this->assertEquals(123, static::$message->gengoOrderId());
    }

}
