<?php


namespace App\Translation;


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
class FormUploadedFile extends UploadedFile implements AttachmentFile
{

    /**
     * Hash name.
     *
     * @return string
     */
    public function getHashName()
    {
        return $this->hashName();
    }

    /**
     * Original name.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->getClientOriginalName();
    }

    /**
     * File size.
     *
     * @return int|null
     */
    public function getFileSize()
    {
        return $this->getClientSize();
    }
}