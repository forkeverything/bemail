<?php

namespace App\Translation;

use App\Translation\Contracts\AttachmentFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostmarkAttachmentFile implements AttachmentFile
{
    /**
     * JSON data.
     *
     * The JSON data we get from Postmark for
     * each attachment.
     *
     *
     * @var
     */
    private $json;

    /**
     * Hash name for file.
     *
     * @var
     */
    private $hash;

    /**
     * Actual file data.
     *
     * @var
     */
    private $data;

    /**
     * PostmarkAttachment constructor.
     *
     * JSON Data is the data we get from Postmark inbound mail.
     *
     * @param $jsonData
     */
    public function __construct($jsonData)
    {
        $this->json = $jsonData;
    }

    /**
     * Get the extension out using original file name.
     *
     * Should be considered safe, same as how Symfony does it for UploadedFile.
     * Another option would be to 'guess' the extension using mime type
     * and mime type guesser class.
     *
     * @return mixed
     */
    public function getFileExtension()
    {
        return pathinfo($this->getOriginalName(), PATHINFO_EXTENSION);
    }

    /**
     * Sets random hash name.
     *
     * Uses 40 char random string generated using Laravel's helper. Same as how
     * Laravel does it.
     *
     */
    protected function setHash()
    {
        $this->hash = Str::random(40) . "." . $this->getFileExtension();
    }


    /**
     * Set file data.
     *
     * Postmark base64 encode's attachment data and stores it in
     * a "Content" property.
     */
    protected function setData()
    {
        if (! $data = base64_decode($this->json["Content"])) {
            $this->data = $data;
        } else {
            throw new \Exception("Could not decode data.");
        }
    }

    /**
     * Get file data.
     *
     * If already stored, we'll just return that to save from
     * multiple decodes.
     *
     * @return mixed
     */
    public function getData()
    {
        if(! $this->data) $this->setData();
        return $this->data;
    }

    /**
     * Store attached file on a filesystem disk.
     *
     * @param $directory
     * @return bool|string
     */
    public function store($directory)
    {
        $fullPath = "{$directory}/{$this->getHashName()}";
        return Storage::put($fullPath, $this->getData()) ? $fullPath : false;
    }

    /**
     * Hash name.
     *
     * @return string
     */
    public function getHashName()
    {
        if(! $this->hash) $this->setHash();
        return $this->hash;
    }

    /**
     * Original name.
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->json["Name"];
    }

    /**
     * File size.
     *
     * @return int|null
     */
    public function getFileSize()
    {
        return strlen($this->getData());
    }
}