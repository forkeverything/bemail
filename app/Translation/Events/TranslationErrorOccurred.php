<?php

namespace App\Translation\Events;

use App\Translation\Exceptions\TranslatorException\MessageCouldNotBeTranslatedException;
use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TranslationErrorOccurred
{
    use Dispatchable, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    /**
     * @var MessageCouldNotBeTranslatedException
     */
    public $exception;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param MessageCouldNotBeTranslatedException $exception
     */
    public function __construct(Message $message, \Exception $exception)
    {
        $this->message = $message;
        $this->exception = $exception;
    }
}
