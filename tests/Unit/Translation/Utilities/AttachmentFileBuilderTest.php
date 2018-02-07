<?php

namespace Tests\Unit\Translation\Utilities;

use App\Translation\Contracts\AttachmentFile;
use App\Translation\Utilities\AttachmentFileBuilder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttachmentFileBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function it_converts_an_array_of_uploaded_file_instances()
    {
        $path = 'some/path';
        $originalName = 'foo';
        $mime = null;
        $size = null;
        $error = true;       // Set to true - to avoid checking to see if actual file exists.
        $uploadedFiles = [
            new UploadedFile($path, $originalName, $mime, $size, $error),
            new UploadedFile($path, $originalName, $mime, $size, $error),
            new UploadedFile($path, $originalName, $mime, $size, $error)
        ];

        $this->arrayOfAttachmentFiles(AttachmentFileBuilder::convertArrayOfUploadedFiles($uploadedFiles));
    }

    /**
     * @test
     */
    public function it_creates_attachment_files_from_postmark_json()
    {
        $postmarkAttachments = [
            ["name" => "foobar"],
            ["name" => "foobar"]
        ];
        $this->arrayOfAttachmentFiles(AttachmentFileBuilder::createPostmarkAttachmentFiles($postmarkAttachments));
    }

    /**
     * Check if every item in array is an instance of AttachmentFile.
     *
     * @param $array
     */
    protected function arrayOfAttachmentFiles($array)
    {
        foreach ($array as $item) {
            $this->assertInstanceOf(AttachmentFile::class, $item);
        }
    }
}
