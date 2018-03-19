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
                              ->subject($event->request->subject)
                              ->body($event->request->body)
                              ->autoTranslateReply(!!$event->request->auto_translate_reply)
                              ->sendToSelf(!!$event->request->send_to_self)
                              ->langSrcId(Language::findByCode($event->request->lang_src)->id)
                              ->langTgtId(Language::findByCode($event->request->lang_tgt)->id)
                              ->make();
    }
}
