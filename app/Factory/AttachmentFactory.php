<?php


namespace App\Factory;


use App\Attachment;
use App\Message;
use Illuminate\Http\UploadedFile;

class AttachmentFactory
{

    /**
     * Where to store attached file.
     *
     * @var
     */
    protected $directory;

    /**
     * Complete path to physical file.
     *
     * @var
     */
    protected $path;

    /**
     * The UploadedFile.
     *
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile;
     */
    protected $uploadedFile;

    /**
     * Message to attach file to.
     *
     * @var Message
     */
    protected $message;

    /**
     * AttachmentFactory constructor.
     *
     * @param Message $message
     * @param UploadedFile $uploadedFile
     */
    public function __construct(Message $message, UploadedFile $uploadedFile)
    {
        $this->message = $message;
        $this->uploadedFile = $uploadedFile;
    }

    /**
     * Set the directory to store physical file.
     *
     * @return $this
     */
    protected function setDirectory()
    {
        // Want to different root directories in case
        // we might be live on remote server but
        // still in development.
        $environment = env('APP_ENV', 'local');

        $this->directory = "{$environment}/user/{$this->message->user_id}/messages/{$this->message->id}/attachments";

        return $this;
    }

    /**
     * Move physical file to disk.
     *
     * @return $this
     */
    protected function moveFile()
    {
        // The store() method generates a unique name for the file
        // automatically.
        $this->path = $this->uploadedFile->store($this->directory);
        return $this;
    }

    /**
     * Create Attachment Model
     */
    protected function createModel()
    {
        $this->message->attachments()->create([
            'file_name' => $this->uploadedFile->hashName(),
            'original_file_name' => $this->uploadedFile->getClientOriginalName(),
            'path' => $this->path,
            'size' => $this->uploadedFile->getClientSize()
        ]);
    }

    /**
     * Make an Attachment
     */
    public function make()
    {
        $this->setDirectory()
             ->moveFile()
             ->createModel();
    }
}