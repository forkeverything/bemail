<?php

namespace Tests\Unit;

use App\Translation\Message;
use App\Translation\TranslationStatus;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MessageTest extends TestCase
{
    /**
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
