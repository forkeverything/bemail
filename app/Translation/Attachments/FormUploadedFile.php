<?php


namespace App\Translation\Attachments;


use App\Translation\Contracts\AttachmentFile;
use Illuminate\Http\UploadedFile;

/**
 * Uploaded file from compose form.
 *
 * Just a sub-class of Laravel/Symfony's UploadedFiles
 * so that our AttachmentFactory can rely on the
 * AttachmentFile interface.
 *
 * @package App\Translation
 */
class FormUploadedFile implements AttachmentFile
{

    /**
     * Laravel's UploadedFile class.
     *
     * @var
     */
    protected $uploadedFile;

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * Hash name.
     *
     * @return string
     */
    public function hashName()
    {
        return $this->uploadedFile->hashName();
    }

    /**
     * Original name.
     *
     * @return string
     */
    public function originalName()
    {
        return $this->uploadedFile->getClientOriginalName();
    }

    /**
     * File size.
     *
     * @return int|null
     */
    public function fileSize()
    {
        return $this->uploadedFile->getClientSize();
    }

    /**
     * Move file to disk.
     *
     * @param $directory
     * @return string|false
     */
    public function store($directory)
    {
        return $this->uploadedFile->store($directory);
    }
}