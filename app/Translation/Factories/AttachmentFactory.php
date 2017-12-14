<?php


namespace App\Translation\Factories;


use App\Translation\Contracts\AttachmentFile;
use App\Translation\Http\FormUploadedFile;
use App\Translation\Http\PostmarkAttachmentFile;
use App\Translation\Message;
use Illuminate\Http\UploadedFile;

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
            'file_name' => $this->attachmentFile->getHashName(),
            'original_file_name' => $this->attachmentFile->getOriginalName(),
            'path' => $this->path,
            'size' => $this->attachmentFile->getFileSize()
        ]);
    }

    public static function makeFromPostmarkAttachment(PostmarkAttachmentFile $postmarkAttachmentFile)
    {
        $factory = new static();
        $factory->attachmentFile = $postmarkAttachmentFile;
        return $factory;
    }

    public static function makeFromUploadedFile(FormUploadedFile $uploadedFile)
    {
        $factory = new static();
        $factory->attachmentFile = $uploadedFile;
        return $factory;
    }

    /**
     * Name of the method to call for given attachment file.
     *
     * @param $attachment array
     * @return string
     */
    public static function attachmentTypeMethodName($attachment)
    {
        if($attachment instanceof UploadedFile) {
            return "makeFromUploadedFile";
        } else {
            return "makeFromPostmarkAttachment";
        }
    }

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