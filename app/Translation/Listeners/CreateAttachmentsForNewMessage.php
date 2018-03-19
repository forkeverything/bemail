<?php

namespace App\Translation\Listeners;

use App\Translation\Attachments\FormUploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAttachmentsForNewMessage
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $attachmentFiles = FormUploadedFile::convertArray($event->request->attachments);
        $event->newAttachments()
                ->attachmentFiles($attachmentFiles)
                ->make();
    }
}
