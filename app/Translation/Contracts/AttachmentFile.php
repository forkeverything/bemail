<?php

namespace App\Translation\Contracts;

interface AttachmentFile
{
    /**
     * Hash name.
     *
     * @return string
     */
    public function getHashName();

    /**
     * Original name.
     *
     * @return string
     */
    public function getOriginalName();

    /**
     * File size.
     *
     * @return int|null
     */
    public function getFileSize();

    /**
     * Move file to disk.
     *
     * @param $directory
     * @return string|false
     */
    public function store($directory);
}