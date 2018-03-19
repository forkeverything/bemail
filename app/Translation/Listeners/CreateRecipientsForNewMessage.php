<?php

namespace App\Translation\Listeners;

use App\Translation\Factories\RecipientFactory\RecipientEmails;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRecipientsForNewMessage
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $recipientEmails = RecipientEmails::new()->addListOfStandardEmails($event->request->recipients);
        $event->newRecipients()
                ->recipientEmails($recipientEmails)
                ->make();
    }
}
