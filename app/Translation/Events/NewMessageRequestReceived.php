<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Contracts\Translation\Translator;
use App\Translation\Message;
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
     * @var CreateMessageRequest
     */
    public $request;
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
     * @param CreateMessageRequest $request
     * @param Translator $translator
     */
    public function __construct(CreateMessageRequest $request, Translator $translator)
    {
        $this->request = $request;
        $this->translator = $translator;
    }
}
