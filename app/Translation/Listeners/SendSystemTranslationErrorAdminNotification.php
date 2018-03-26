<?php

namespace App\Translation\Listeners;

use App\Translation\Mail\SystemTranslationErrorAdminNotification;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendSystemTranslationErrorAdminNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // TODO ::: Implement multiple admins
        Mail::to(User::where('email', 'mike@bemail.io')->first())
            ->send(new SystemTranslationErrorAdminNotification($event->message));
    }
}
