<?php

namespace App\Translation\Listeners;

use App\Translation\Events\NewMessageRequestReceived;
use App\Translation\Factories\MessageFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateNewMessageModel
{

    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived $event
     */
    public function handle($event)
    {
        $event->message = MessageFactory::new($event->request)
                                        ->owner($event->user)
                                        ->make();
    }
}
