<?php

namespace App\Translation\Factories;

use App\Contracts\Translation\AttachmentFile;
use App\Translation\Message;
use Illuminate\Database\Eloquent\Collection;

class AttachmentFactory
{

    /**
     * Message to attach file to.
     *
     * @var Message
     */
    protected $message;

    /**
     * Where to physically store the files.
     *
     * @var string
     */
    protected $directory;

    /**
     * @var array
     */
    protected $attachmentFiles = [];

    /**
     * Newly created Attachment(s).
     *
     * @var Collection
     */
    protected $attachments;

    /**
     * Create AttachmentFactory instance.
     *
     * @param Message $message
     * @param AttachmentFile $attachmentFile
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->setDirectory();
        $this->attachments = new Collection();
    }

    /**
     * Set the storage directory.
     *
     * @return $this
     */
    protected function setDirectory()
    {
        // Differentiate root directories based on app status
        // as a precaution against messing up live data.
        $environment = env('APP_ENV', 'local');
        $this->directory = "{$environment}/user/{$this->message->user_id}/messages/{$this->message->id}/attachments";
        return $this;
    }

    /**
     * File instances to create as Attachment(s).
     *
     * @param null|array $attachmentFiles
     * @return $this|array
     */
    public function attachmentFiles($attachmentFiles = null)
    {
        if (is_null($attachmentFiles)) {
            return $this->attachmentFiles;
        }
        $this->attachmentFiles = $attachmentFiles;
        return $this;
    }

    /**
     * Move file into directory.
     *
     * @param AttachmentFile $attachmentFile
     * @return false|string
     */
    protected function moveFile(AttachmentFile $attachmentFile)
    {
        return $attachmentFile->store($this->directory);
    }

    /**
     * Create Attachment model.
     *
     * @param AttachmentFile $attachmentFile
     * @param $path
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createAttachment(AttachmentFile $attachmentFile, $path)
    {
        return $this->message->attachments()->create([
            'file_name' => $attachmentFile->hashName(),
            'original_file_name' => $attachmentFile->originalName(),
            'path' => $path,
            'size' => $attachmentFile->fileSize()
        ]);
    }

    /**
     * Make Attachment(s).
     *
     * @return Collection
     */
    public function make()
    {
        foreach ($this->attachmentFiles as $attachmentFile) {
            $path = $this->moveFile($attachmentFile);
            $attachment = $this->createAttachment($attachmentFile, $path);
            $this->attachments->push($attachment);
        }
        return $this->attachments;
    }


}