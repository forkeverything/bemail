<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Translation\Message;
use App\User;
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
     * @var Message|null
     */
    public $message;
    /**
     * @var CreateMessageRequest
     */
    public $request;
    /**
     * @var User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param CreateMessageRequest $request
     * @param User $user
     * @param Message $message
     */
    public function __construct(CreateMessageRequest $request, User $user, $message = null)
    {

        $this->request = $request;
        $this->user = $user;
        $this->message = $message;
    }
}
