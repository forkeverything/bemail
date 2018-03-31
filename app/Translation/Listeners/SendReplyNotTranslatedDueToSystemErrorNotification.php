<?php

namespace App\Translation\Listeners;

use App\Translation\Events\FailedCreatingReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReplyNotTranslatedDueToSystemErrorNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FailedCreatingReply  $event
     * @return void
     */
    public function handle(FailedCreatingReply $event)
    {
        //
    }
}
