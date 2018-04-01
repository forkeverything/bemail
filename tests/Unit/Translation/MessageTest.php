<?php

namespace Tests\Unit;

use App\Error;
use App\Payment\Receipt;
use App\Translation\Attachment;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Factories\RecipientFactory;
use App\Translation\Message;
use App\Translation\Order;
use App\Translation\Recipient;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @var Message
     */
    private $message;

    public function setUp()
    {
        parent::setUp();
        $this->message = factory(Message::class)->create([
            'message_id' => factory(Message::class)->create()->id
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
            'sender_email' => 'foo@example.com',
            'sender_name' => 'John Dough',
            'user_id' => factory(User::class)->create()->id,
            'message_id' => factory(Message::class)->create()->id,
            'lang_src_id' => 1,
            'lang_tgt_id' => 2
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
            'has_recipients',
            'readable_created_at'
        ];
        foreach ($dynamicProperties as $property) {
            $this->assertContains($property, array_keys($this->message->toArray()));
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
            $this->assertInstanceOf('Illuminate\Support\Carbon', $this->message->{$field});
        }
    }

    /**
     * @test
     */
    public function it_fetches_the_message_owner()
    {
        $this->assertInstanceOf('App\User', $this->message->owner);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_the_message()
    {
        $this->assertCount(0, $this->message->recipients);
        factory(Recipient::class, 5)->create(['message_id' => $this->message->id]);
        $this->assertCount(5, $this->message->fresh()->recipients);
    }

    /**
     * @test
     */
    public function it_fetches_the_original_message()
    {
        $this->assertInstanceOf(Message::class, $this->message->originalMessage);
    }

    /**
     * @test
     */
    public function it_fetches_all_replies()
    {
        $message = factory(Message::class)->create();
        $this->assertCount(0, $message->replies);
        $reply = factory(Message::class, 5)->create(['message_id' => $message->id]);
        $this->assertCount(5, $message->fresh()->replies);
    }

    /**
     * @test
     */
    public function it_fetches_message_attachments()
    {
        $this->assertCount(0, $this->message->attachments);
        factory(Attachment::class, 3)->create([
            'message_id' => $this->message->id
        ]);
        $this->assertCount(3, $this->message->fresh()->attachments);
    }

    /**
     * @test
     */
    public function it_has_a_source_language()
    {
        $this->assertInstanceOf('App\Language', $this->message->sourceLanguage);
    }

    /**
     * @test
     */
    public function it_has_a_target_language()
    {
        $this->assertInstanceOf('App\Language', $this->message->targetLanguage);
    }

    /**
     * @test
     */
    public function it_fetches_a_receipt()
    {
        $this->assertNull($this->message->receipt);
        $receipt = factory(Receipt::class)->create([
            'message_id' => $this->message
        ]);
        $this->assertEquals($receipt->id, $this->message->fresh()->receipt->id);
    }

    /**
     * @test
     */
    public function it_fetches_the_translation_order()
    {
        $this->assertNull($this->message->order);
        factory(Order::class)->create([
            'message_id' => $this->message->id
        ]);
        $this->assertNotNull($this->message->fresh()->order);
    }

    /**
     * @test
     */
    public function it_checks_whether_message_is_for_a_reply()
    {
        $this->assertTrue($this->message->isReply());
    }

    /**
     * @test
     */
    public function it_checks_whether_there_are_recipients()
    {
        $this->assertFalse($this->message->has_recipients);
        factory(Recipient::class)->create(['message_id' => $this->message->id]);
        $this->assertTrue($this->message->fresh()->has_recipients);
    }

    /**
     * @test
     */
    public function it_formats_readable_created_at_correctly()
    {
        $this->message->created_at = '2018-01-01 00:00:00';
        $this->message->save();
        $this->assertEquals('Jan 1, 00:00 UTC', $this->message->fresh()->readable_created_at);
    }

    /** @test */
    public function it_fetches_error()
    {
        $this->assertNull($this->message->error);
        $this->message->newError()
                      ->code('8888')
                      ->msg('something terrible happened.')
                      ->save();
        $this->assertNotNull($this->message->fresh()->error);
    }

    /** @test */
    public function it_instantiates_a_new_error()
    {
        $this->assertInstanceOf(Error::class, $this->message->newError());
    }


    /**
     * @test
     */
    public function it_instantiates_recipients_factory()
    {
        $this->assertInstanceOf(RecipientFactory::class, $this->message->newRecipients());
    }

    /**
     * @test
     */
    public function it_instantiates_attachment_factory()
    {
        $this->assertInstanceOf(AttachmentFactory::class, $this->message->newAttachments());
    }

    /**
     * @test
     */
    public function it_returns_translated_body()
    {
        $this->message->translatedBody('foobar');
        $this->assertEquals('foobar', $this->message->translatedBody());
    }

    /**
     * @test
     */
    public function it_gets_the_message_thread()
    {
        $this->assertInstanceOf(Collection::class, $this->message->thread());
    }

    /**
     * @test
     */
    public function it_instantiates_a_new_order()
    {
        $this->assertInstanceOf(Order::class, $this->message->newOrder());
    }

    /**
     * @test
     */
    public function it_instantiates_a_new_receipt()
    {
        $this->assertInstanceOf(Receipt::class, $this->message->newReceipt());
    }

    /**
     * @test
     */
    public function it_checks_whether_the_sender_is_the_owner()
    {
        $this->assertFalse($this->message->senderIsTheOwner());
        $ownerEmail = $this->message->owner->email;
        $this->message->sender_email = $ownerEmail;
        $this->assertTrue($this->message->senderIsTheOwner());
    }

}
