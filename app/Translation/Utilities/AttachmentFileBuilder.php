<?php

namespace App\Translation\Utilities;

use App\Translation\Attachments\FormUploadedFile;
use App\Translation\Attachments\PostmarkAttachmentFile;

/**
 * AttachmentFileBuilder
 * Creates AttachmentFile class via various methods.
 *
 * @package App\Translation\Utilities
 */
class AttachmentFileBuilder
{
    /**
     * Takes an array of UploadedFile(s) and converts
     * each item into a FormUploadedFile instance.
     *
     * @param $array
     * @return array
     */
    public static function convertArrayOfUploadedFiles($array)
    {
        return array_map(function ($uploadedFile) {
            return new FormUploadedFile($uploadedFile);
        }, $array);
    }

    /**
     * Creates an Array of PostmarkAttachmentFile(s) from JSON.
     *
     * @param $attachments
     * @return array
     */
    public static function createPostmarkAttachmentFiles($attachments)
    {
        return array_map(function ($attachmentJson) {
            return new PostmarkAttachmentFile($attachmentJson);
        }, $attachments);
    }

}