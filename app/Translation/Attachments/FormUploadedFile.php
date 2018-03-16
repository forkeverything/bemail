<?php


namespace App\Translation\Attachments;


use App\Translation\Contracts\AttachmentFile;
use Illuminate\Http\UploadedFile;

/**
 * Uploaded file from compose form.
 *
 * Custom class that defers to UploadedFile instance methods.
 * Created to be able to implement AttachmentFile without
 * modifying UploadedFile directly.
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