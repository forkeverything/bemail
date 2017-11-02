<?php

namespace Tests\Unit\Translation\Factories;

use App\Http\Requests\CreateMessageRequest;
use App\Language;
use App\Translation\Factories\MessageFactory;
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
        $formFields = [
            'recipients' => 'sam@bemail.io,john@bemail.io',
            'subject' => 'Super important message.',
            'body' => 'Please translate this.',
            'auto_translate_reply' => 'on',
//            'send_to_self' => null,                               // Intentionally left blank (unchecked checkbox isn't sent in request)
            'lang_src' => $sourceLanguage->code,
            'lang_tgt' => $targetLanguage->code
        ];

        $fakeRequest = new CreateMessageRequest($formFields);

        $message = MessageFactory::makeNewMessage($fakeRequest, $user);

        // Check Message Model fields are stored correctly
        $this->assertEquals($formFields['subject'], $message->subject);
        $this->assertEquals($formFields['body'], $message->body);
        $this->assertEquals(1, $message->auto_translate_reply);
        $this->assertEquals(0, $message->send_to_self);
        $this->assertEquals($sourceLanguage->id, $message->lang_src_id);
        $this->assertEquals($targetLanguage->id, $message->lang_tgt_id);
        $this->assertEquals(TranslationStatus::available()->id, $message->translation_status_id);

        // Check recipients
        $this->assertCount(2, $message->recipients);
        $this->assertNotNull($message->recipients()->where('email', 'sam@bemail.io')->first());
        $this->assertNotNull($message->recipients()->where('email', 'john@bemail.io')->first());
    }
}
