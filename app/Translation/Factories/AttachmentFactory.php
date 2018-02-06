<?php


namespace App\Translation\Factories;


use App\Translation\Contracts\AttachmentFile;
use App\Translation\Message;

class AttachmentFactory
{

    /**
     * @var AttachmentFile
     */
    protected $attachmentFile;

    /**
     * Complete path to physical file.
     *
     * @var
     */
    protected $path;

    /**
     * Message to attach file to.
     *
     * @var Message
     */
    protected $message;

    /**
     * File storage directory path.
     *
     * @return string
     */
    protected function directory()
    {
        // Differentiate root directories based on app status
        // as a precaution against messing up live data.
        $environment = env('APP_ENV', 'local');
        return "{$environment}/user/{$this->message->user_id}/messages/{$this->message->id}/attachments";
    }

    /**
     * Create Attachment Model
     */
    protected function createModel()
    {
        return $this->message->attachments()->create([
            'file_name' => $this->attachmentFile->hashName(),
            'original_file_name' => $this->attachmentFile->originalName(),
            'path' => $this->path,
            'size' => $this->attachmentFile->fileSize()
        ]);
    }

    /**
     * AttachmentFile to create an Attachment from.
     *
     * @param AttachmentFile $attachmentFile
     * @return static
     */
    public static function from(AttachmentFile $attachmentFile){
        $factory = new static();
        $factory->attachmentFile = $attachmentFile;
        return $factory;
    }

    /**
     * Message model the Attachment should be attached to.
     *
     * @param Message $message
     * @return $this
     */
    public function for(Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Make an Attachment
     */
    public function make()
    {
        // Where to store file?
        $directory = $this->directory();
        $this->path = $this->attachmentFile->store($directory);
        return $this->createModel();
    }

}