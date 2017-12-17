<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\RecipientTranslatedMessage;
use App\Translation\Mail\SenderTranslatedMessage;
use App\Translation\Message;
use App\Translation\RecipientType;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendTranslatedMessageMail
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Send notification emails
        if($event->message->send_to_self) {
            // Send translated message back to sender
            Mail::to($event->message->user)->send(new SenderTranslatedMessage($event->message));
        } else {
            // Send to recipients
            Mail::to($this->buildMailAddresses($event->message, RecipientType::standard()))
                ->cc($this->buildMailAddresses($event->message, RecipientType::cc()))
                ->bcc($this->buildMailAddresses($event->message, RecipientType::bcc()))
                ->send(new RecipientTranslatedMessage($event->message));
            // TODO(?) ::: When the message is a reply, send a different mail to indicate
            // a reply.
        }
    }

    protected function buildMailAddresses(Message $message, RecipientType $type)
    {
        $addresses = [];
        foreach ($message->recipients->where('recipient_type_id', $type->id) as $recipient) {
            array_push($addresses, ['email' => $recipient->email]);
        }
        // If we're building for the 'to' field and Message is a reply, also send to original sender.
        if($type->id == RecipientType::standard()->id && $message->is_reply)  {
            array_push($addresses, ['email' => $message->user->email]);
        }
        // Log out to see why we're not sending to cc's
        \Log::info('BUILT OUTBOUND ADDRESSES', [
            'recipient_type' => $type->name,
            'addresses' => $addresses
        ]);
        return $addresses;
    }

}
