<?php

namespace Tests\Unit\Translation\Factories;

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

        // Dummy attributes we expect to end up with
        $attributes = [
            'file_name' => 'foobar',
            'original_file_name' => 'some_file.txt',
            'path' => 'path/to/file',
            'size' => 55555
        ];

        $message = factory(Message::class)->create();
        $uploadedFile = \Mockery::mock('Illuminate\Http\UploadedFile');

        // Assert that we're moving the file as well as setting the same
        // directory as we expect here.
        $uploadedFile->shouldReceive('store')
                     ->once()
                     ->with("{$environment}/user/{$message->user_id}/messages/{$message->id}/attachments")
                     ->andReturn($attributes['path']);
        $uploadedFile->shouldReceive('getClientOriginalName')
                     ->once()
                     ->andReturn($attributes['original_file_name']);
        $uploadedFile->shouldReceive('hashName')
                     ->once()
                     ->andReturn($attributes['file_name']);
        $uploadedFile->shouldReceive('getClientSize')
                     ->once()
                     ->andReturn($attributes['size']);

        $attachment = (new AttachmentFactory($message, $uploadedFile))->make();

        foreach ($attributes as $attribute => $value) {
            $this->assertEquals($value, $attachment->{$attribute});
        }

    }
}
