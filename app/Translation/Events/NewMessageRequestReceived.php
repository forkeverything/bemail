<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Contracts\Translation\Translator;
use App\Translation\Message;
use App\Translation\Message\NewMessageFields;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageRequestReceived
{
    use Dispatchable, SerializesModels;

    /**
     * Values for the new Message.
     *
     * @var NewMessageFields
     */
    public $fields;
    /**
     * @var Translator
     */
    public $translator;
    /**
     * Message model after being created.
     *
     * @var Message
     */
    public $message;

    /**
     * NewMessageRequestReceived constructor.
     *
     * @param NewMessageFields $fields
     * @param Translator $translator
     */
    public function __construct(NewMessageFields $fields, Translator $translator)
    {
        $this->fields = $fields;
        $this->translator = $translator;
    }
}
