<?php

namespace Tests\Unit\Translation\Factories;

use App\Contracts\Translation\AttachmentFile;
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
     * @var Message
     */
    private $message;

    /**
     * @var AttachmentFactory
     */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->message = factory(Message::class)->create();
        $this->factory = new AttachmentFactory($this->message);
    }

    /**
     * @test
     */
    public function it_instantiates_with_a_message()
    {
        $this->assertInstanceOf(AttachmentFactory::class, $this->factory);
    }


    /**
     * @test
     */
    public function it_has_attachment_files()
    {
        $attachmentFiles = [
            'foo',
            'bar',
            'baz'
        ];
        $this->assertCount(0, $this->factory->attachmentFiles());
        $this->factory->attachmentFiles($attachmentFiles);
        $this->assertCount(3, $this->factory->attachmentFiles());
    }

    /**
     * @test
     */
    public function it_makes_an_attachment()
    {

        $this->assertCount(0, $this->message->attachments);

        $attachmentFile = \Mockery::mock(AttachmentFile::class);

        $attributes = [
            'file_name' => 'foobar',
            'original_file_name' => 'some_file.txt',
            'path' => 'path/to/file',
            'size' => 55555
        ];

        // Checks both calling store() and the directory is set.
        $attachmentFile->shouldReceive('store')
                       ->once()
                       ->with("local/user/{$this->message->user_id}/messages/{$this->message->id}/attachments")
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

        $attachmentFiles = [$attachmentFile];
        $this->factory->attachmentFiles($attachmentFiles)->make();

        $this->assertCount(1, $this->message->fresh()->attachments);
    }



}
