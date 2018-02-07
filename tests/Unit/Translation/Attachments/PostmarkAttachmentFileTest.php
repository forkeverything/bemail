<?php

namespace Tests\Unit\Translation\Attachments;

use App\Translation\Attachments\PostmarkAttachmentFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostmarkAttachmentFileTest extends TestCase
{

    use DatabaseTransactions;

    private static $postmarkAttachmentFile;

    public function setUp()
    {
        parent::setUp();
        $attachmentJson = [
            "Content" => "/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDAAUDBAQEAwUEBAQFBQUGBwwIBwcHBw8LCwkMEQ8SEhEPERETFhwXExQaFRERGCEYGh0dHx8fExciJCIeJBweHx7/2wBDAQUFBQcGBw4ICA4eFBEUHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh7/wAARCAAwADADASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/8QAHwEAAwEBAQEBAQEBAQAAAAAAAAECAwQFBgcICQoL/8QAtREAAgECBAQDBAcFBAQAAQJ3AAECAxEEBSExBhJBUQdhcRMiMoEIFEKRobHBCSMzUvAVYnLRChYkNOEl8RcYGRomJygpKjU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6goOEhYaHiImKkpOUlZaXmJmaoqOkpaanqKmqsrO0tba3uLm6wsPExcbHyMnK0tPU1dbX2Nna4uPk5ebn6Onq8vP09fb3+Pn6/9oADAMBAAIRAxEAPwDg7nQ9XlmtLWPTbku9wg5jIA5zknsPevbvBXhjTdPRXvJUubleW5+RT7D+prlRq32WFpWb5+w9qzrTxFMrqyySlyMhR1J/Dmvl62aNyUGtArNqXLE9+tNSs4IFRpB6cdfpWX4slsdQszGzblUhlZTgow6Mp9Qa8lXxFqEeQ0bRrkACVtgyfYkUsmu3sbhneHDHgeepz9eaueLpyp8ljD2VTojSuLhNQeWz1FwL+3+5Oox5qdmI/nWDdu0bPC55U4NUp9Tkn1aGSMoZERg21wfl69vxrT0fTpPEV61skyx3WwtFu4D46g+9eLWwbrNKC978z0sFiJUXyVNvyOR1q4un1E2sTFV3Dn2xzVPxN4qbwwZoI7Ca5iwpMm8ouCDnBHNb99FG80h2jcQPyq9p+lQanb7poEkOMHcM9AAQfyruwc4OpaSuPl/eNI47Rtf0DxKkV4iNFcQuu+OX1/vA/wAQ7ZFHjrxNc6fbQWfh+wM9wSz7kiL+SvQHb6nP6VoeJfDjxTi80i1U/ZQdwGApXHIAHXFS+EdNuJ7U6vfwqUuiDHsJ3bBwMg/nXalBS5re72OhwbjYzfAEfiImK91i6maWSVd8WFVQp4xwOeDzXXeE79tO8W2xHL293sI/2Twf0JrVuLa1t7FZFUgLgjjvmuZmRk8bXAU9HLfjsJrGtUftIzRxV4bF+ewvjI0hsZyFPeM4xT7HUlsLS6OGKqpfjqB3rqvFOszaZZkQ8tIhB78e3vXl15qpgnWGGPDj5pFJyCT2zWMMHKmlNanRUfNLmOl0nVbTW7XYsvkRHtnDGpgINKgCQXO4AdCcjFc5p9iNzTonlbuVQnpV9bIRQt50oZic5zx+FdShJo1UtCZtTfUNXsrN+I3cMwHcDn+lJApk8QXt86OyKG5UZxngH8s0y3gjF0bu3kTzQhVQTwuRjNa/hieDS4/MEglcH98c/eHfHtRTpKVVKWyOetG7uj//2Q==",
            "ContentLength" => 1357,
            "ContentType" => "image/jpeg",
            "Name" => "cat.jpeg",
            "ContentID" => ""
        ];
        static::$postmarkAttachmentFile = new PostmarkAttachmentFile($attachmentJson);
    }

    /**
     * @test
     */
    public function it_gets_original_name()
    {
        $this->assertEquals('cat.jpeg', static::$postmarkAttachmentFile->originalName());
    }

    /**
     * @test
     */
    public function it_gets_file_extension()
    {
        $this->assertEquals('jpeg', static::$postmarkAttachmentFile->getFileExtension());
    }

    /**
     * @test
     */
    public function it_makes_hash_name()
    {
        $this->assertNotNull(static::$postmarkAttachmentFile->hashName());
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_gets_attachment_data()
    {
        $this->assertNotNull(static::$postmarkAttachmentFile->getData());
    }

    /**
     * @test
     *
     * @throws \Exception
     */
    public function it_determines_file_size()
    {
        $this->assertEquals('1357', static::$postmarkAttachmentFile->fileSize());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function it_stores_file_with_right_path()
    {
        $hashName = static::$postmarkAttachmentFile->hashName();
        $fullPath = "/some/directory/$hashName";
        $data = static::$postmarkAttachmentFile->getData();
        Storage::shouldReceive('put')
               ->once()
               ->withArgs([
                   $fullPath,
                   $data
               ])
        ->andReturn(true);
        // returns full path on success
        $this->assertEquals($fullPath, static::$postmarkAttachmentFile->store('/some/directory'));
    }

}
