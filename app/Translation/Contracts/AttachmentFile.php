<?php

namespace App\Translation\Contracts;

interface AttachmentFile
{
    /**
     * Hash name.
     *
     * @return string
     */
    public function hashName();

    /**
     * Original name.
     *
     * @return string
     */
    public function originalName();

    /**
     * File size.
     *
     * @return int|null
     */
    public function fileSize();

    /**
     * Move file to disk.
     *
     * @param $directory
     * @return string|false
     */
    public function store($directory);
}