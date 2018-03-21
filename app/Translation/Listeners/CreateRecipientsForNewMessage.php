<?php

namespace App\Translation\Listeners;

use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Factories\RecipientFactory\RecipientEmails;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRecipientsForNewMessage
{

    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived  $event
     * @return void
     */
    public function handle($event)
    {
        $recipientEmails = RecipientEmails::new()->addListOfStandardEmails($event->fields->recipients);
        $event->message->newRecipients()
                ->recipientEmails($recipientEmails)
                ->make();
    }
}
