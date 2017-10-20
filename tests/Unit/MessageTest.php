<?php

namespace Tests\Unit;

use App\GengoError;
use App\Language;
use App\Payments\MessageReceipt;
use App\Translation\Attachment;
use App\Translation\Message;
use App\Translation\Recipient;
use App\Translation\TranslationStatus;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'subject' => 'Super important message',
            'body' => 'Please translate this',
            'translated_body' => 'Some translated text',
            'user_id' => factory(User::class)->create()->id,
            'translation_status_id' => TranslationStatus::available()->id,
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
            'word_count'
        ];
        $message = factory(Message::class)->create();
        foreach ($dynamicProperties as $property) {
            $this->assertContains($property, array_keys($message->toArray()));
        }
    }

    /**
     * @test
     */
    public function it_fetches_user_that_sent_the_message()
    {
        $message = factory(Message::class)->create();
        $this->assertInstanceOf('App\User', $message->sender);
    }

    /**
     * @test
     */
    public function it_fetches_recipients_for_the_message()
    {
        $message = factory(Message::class)->create();
        $this->assertCount(0, $message->recipients);
        $recipientIds = factory(Recipient::class, 5)->create()->pluck('id')->toArray();
        $message->recipients()->sync($recipientIds);
        $this->assertCount(5, $message->fresh()->recipients);
    }

    /**
     * @test
     */
    public function it_fetches_message_attachments()
    {
        $message = factory(Message::class)->create();
        $this->assertCount(0, $message->attachments);
        factory(Attachment::class, 3)->create([
            'message_id' => $message->id
        ]);
        $this->assertCount(3, $message->fresh()->attachments);
    }

    /**
     * @test
     */
    public function it_has_a_translation_status()
    {
        $message = factory(Message::class)->create();
        $this->assertInstanceOf('App\Translation\TranslationStatus', $message->status);
    }

    /**
     * @test
     */
    public function it_has_a_source_language()
    {
        $message = factory(Message::class)->create();
        $this->assertInstanceOf('App\Language', $message->sourceLanguage);
    }

    /**
     * @test
     */
    public function it_has_a_target_language()
    {
        $message = factory(Message::class)->create();
        $this->assertInstanceOf('App\Language', $message->targetLanguage);
    }

    /**
     * @test
     */
    public function it_fetches_a_receipt()
    {
        $message = factory(Message::class)->create();
        $this->assertNull($message->receipt);
        $receipt = factory(MessageReceipt::class)->create([
            'message_id' => $message
        ]);
        $this->assertEquals($receipt->id, $message->fresh()->receipt->id);
    }

    /**
     * @test
     */
    public function it_fetches_all_gengo_errors()
    {
        $message = factory(Message::class)->create();
        $this->assertCount(0, $message->gengoErrors);
        factory(GengoError::class, 10)->create([
            'message_id' => $message->id
        ]);
        $this->assertCount(10, $message->fresh()->gengoErrors);
    }

    /**
     * @test
     */
    public function it_updates_translation_status()
    {
        $status = TranslationStatus::all()->random();
        $message = factory(Message::class)->create();
        $message->updatestatus($status);
        $this->assertEquals($message->translation_status_id, $status->id);
    }

    /**
     * @test
     */
    public function it_gets_the_right_word_count()
    {
        $message = factory(Message::class)->create();
        $this->assertEquals(str_word_count($message->body), $message->word_count);
    }

    /**
     *
     * @test
     */
    public function it_updates_message_translation_status()
    {
        $message = factory(Message::class)->create();
        $translationStatuses = TranslationStatus::all();
        foreach ($translationStatuses as $status) {
            $message->updateStatus($status);
            $this->assertEquals($message->translation_status_id, $status->id);
        }
    }
}
