<?php

namespace App\Translation\Listeners;

use App\Translation\Events\ReplyErrorOccurred;
use App\Translation\Events\ReplyReceived;
use App\Translation\Factories\MessageFactory;
use App\Translation\Reply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class SaveReplyToDatabase
{

    /**
     * Handle the event.
     *
     * @param  ReplyReceived $event
     * @return mixed
     * @throws \Exception
     */
    public function handle($event)
    {
        try {
            $reply = Reply::create([
                'sender_email' => $event->fromAddress,
                'sender_name' => $event->fromName,
                'original_message_id' => $event->originalMessage->id
            ]);

            $event->message = MessageFactory::reply($reply)
                                     ->recipientEmails($event->recipients)
                                     ->subject($event->subject)
                                     ->body($event->body)
                                     ->attachments($event->attachments)
                                     ->make();

        } catch (\Exception $e) {
            event(new ReplyErrorOccurred($event->fromAddress, $event->originalMessage, $event->subject, $event->body));
            if (App::environment('local')) throw $e;
            // Stop propagation, don't want other listeners to run.
            return false;
        }
    }
}
