<?php

namespace App\Translation\Listeners;

use App\Translation\Factories\AttachmentFactory\FormUploadedFile;
use App\Translation\Events\NewMessageRequestReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAttachmentsForNewMessage
{
    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived  $event
     * @return bool
     */
    public function handle($event)
    {
        $attachmentFiles = FormUploadedFile::convertArray($event->fields->attachments);
        $event->message->newAttachments()
                ->attachmentFiles($attachmentFiles)
                ->make();
    }
}
