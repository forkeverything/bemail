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
    public function it_makes_a_message()
    {
        $user = factory(User::class)->create();
        $sourceLanguage = Language::first();
        $targetLanguage = Language::find(2);
        $formFields = [
            'recipients' => 'sam@bemail.io,john@bemail.io',
            'subject' => 'Super important message.',
            'body' => 'Please translate this.',
            'lang_src' => $sourceLanguage->code,
            'lang_tgt' => $targetLanguage->code
        ];

        $fakeRequest = new CreateMessageRequest($formFields);

        $message = (new MessageFactory($fakeRequest, $user))->make();

        // Check Message Model fields are stored correctly
        $this->assertEquals($formFields['subject'], $message->subject);
        $this->assertEquals($formFields['body'], $message->body);
        $this->assertEquals($sourceLanguage->id, $message->lang_src_id);
        $this->assertEquals($targetLanguage->id, $message->lang_tgt_id);
        $this->assertEquals(TranslationStatus::available()->id, $message->translation_status_id);

        // Check recipients
        $this->assertCount(2, $message->recipients);
        $this->assertNotNull($message->recipients()->where('email', 'sam@bemail.io')->first());
        $this->assertNotNull($message->recipients()->where('email', 'john@bemail.io')->first());
    }
}
