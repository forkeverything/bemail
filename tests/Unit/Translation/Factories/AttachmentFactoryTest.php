<?php

namespace Tests\Unit\Translation\Factories;

use App\Translation\Contracts\AttachmentFile;
use App\Translation\Factories\AttachmentFactory;
use App\Translation\Message;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \Mockery;

class AttachmentFactoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_makes_an_attachment()
    {
        $environment = env('APP_ENV', 'local');

        $attributes = [
            'file_name' => 'foobar',
            'original_file_name' => 'some_file.txt',
            'path' => 'path/to/file',
            'size' => 55555
        ];

        $message = factory(Message::class)->create();
        $attachmentFile = \Mockery::mock(AttachmentFile::class);

        // Assert that we're moving the file as well as setting the same
        // directory as we expect here.
        $attachmentFile->shouldReceive('store')
                     ->once()
                     ->with("{$environment}/user/{$message->user_id}/messages/{$message->id}/attachments")
                     ->andReturn($attributes['path']);
        $attachmentFile->shouldReceive('originalName')
                     ->once()
                     ->andReturn($attributes['original_file_name']);
        $attachmentFile->shouldReceive('hashName')
                     ->once()
                     ->andReturn($attributes['file_name']);
        $attachmentFile->shouldReceive('fileSize')
                     ->once()
                     ->andReturn($attributes['size']);

        // Actual attachment model
        $attachment = $message->newAttachment($attachmentFile)->make();

        foreach ($attributes as $attribute => $value) {
            $this->assertEquals($value, $attachment->{$attribute});
        }

    }
}
