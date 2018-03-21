<?php

namespace App\Translation\Listeners;

use App\Language;
use App\Translation\Events\NewMessageRequestReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class CreateNewMessageModel
{

    /**
     * Handle the event.
     *
     * @param  NewMessageRequestReceived  $event
     * @return void
     */
    public function handle($event)
    {
        $event->message = Auth::user()->newMessage()
                              ->subject($event->fields->subject)
                              ->body($event->fields->body)
                              ->autoTranslateReply($event->fields->autoTranslateReply)
                              ->sendToSelf($event->fields->sendToSelf)
                              ->langSrcId($event->fields->langSrcId)
                              ->langTgtId($event->fields->langTgtId)
                              ->make();
    }
}
