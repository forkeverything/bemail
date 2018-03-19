<?php

namespace Tests\Unit;

use App\Translation\Attachment;
use App\Translation\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachmentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_can_mass_assign_these_fields()
    {
        $fields = [
            'file_name' => 'l337',
            'original_file_name' => 'some_file.txt',
            'path' => 'testing/file/attachment/some_file.txt',
            'size' => 2048,
            'message_id' => factory(Message::class)->create()->id
        ];
        $attachment = Attachment::create($fields);
        foreach ($fields as $key => $value) {
            $this->assertEquals($attachment->{$key}, $value);
        }
    }

    /**
     * @test
     */
    public function it_finds_the_message_it_is_attached_to()
    {
        $message = factory(Message::class)->create();
        $attachment = factory(Attachment::class)->create([
            'message_id' => $message
        ]);
        $this->assertEquals($attachment->message->id, $message->id);
    }

}
