<?php

namespace App\Translation\Events;

use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageTranslated
{
    use Dispatchable, SerializesModels;

    /**
     * Message model that's been translated.
     *
     * @var Message
     */
    public $message;

    /**
     * Text that's been translated.
     *
     * @var
     */
    public $translatedText;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     * @param $translatedText
     */
    public function __construct(Message $message, $translatedText)
    {
        $this->message = $message;
        $this->translatedText = $translatedText;
    }
}
