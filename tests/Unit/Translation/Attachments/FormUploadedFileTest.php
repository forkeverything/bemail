<?php

namespace Tests\Unit\Translation\Attachments;

use App\Translation\Attachments\FormUploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Mockery\Mock;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FormUploadedFileTest extends TestCase
{
    use DatabaseTransactions;

    // Mock UploadedFile
    private static $uploadedFile;

    public function setUp()
    {
        parent::setUp();
        static::$uploadedFile = \Mockery::mock(UploadedFile::class);
    }

    /**
     * @test
     */
    public function it_gets_hash_name()
    {
        static::$uploadedFile->shouldReceive('hashName')
                             ->once()
                             ->withNoArgs()
                             ->andReturn('foobar');

        $this->assertEquals('foobar', (new FormUploadedFile(static::$uploadedFile))->hashName());
    }

    /**
     * @test
     */
    public function it_gets_the_original_name()
    {
        static::$uploadedFile->shouldReceive('getClientOriginalName')
                             ->once()
                             ->withNoArgs()
                             ->andReturn('baz');

        $this->assertEquals('baz', (new FormUploadedFile(static::$uploadedFile))->originalName());
    }

    /**
     * @test
     */
    public function it_gets_file_size()
    {
        static::$uploadedFile->shouldReceive('getClientSize')
                             ->once()
                             ->withNoArgs()
                             ->andReturn('88888');

        $this->assertEquals('88888', (new FormUploadedFile(static::$uploadedFile))->fileSize());
    }

    /**
     * @test
     */
    public function it_calls_store_on_uploaded_file()
    {
        static::$uploadedFile->shouldReceive('store')
                             ->once()
                             ->withArgs(['/some/file/path']);

        (new FormUploadedFile(static::$uploadedFile))->store('/some/file/path');
    }
}
