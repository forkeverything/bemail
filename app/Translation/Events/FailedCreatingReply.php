<?php

namespace App\Translation\Events;

use App\Translation\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FailedCreatingReply
{
    use Dispatchable, SerializesModels;

    /**
     * Include thread from the original Message.
     *
     * @var Collection
     */
    public $threadMessages;
    /**
     * The subject of the reply.
     *
     * @var string
     */
    public $subject;
    /**
     * The reply body.
     *
     * @var string
     */
    public $body;
    /**
     * Reply email standard 'to' recipients.
     *
     * @var array
     */
    public $standardRecipients;
    /**
     * Reply email 'cc' recipients.
     *
     * @var array
     */
    public $ccRecipients;
    /**
     * Reply email 'bcc' recipients.
     *
     * @var array
     */
    public $bccRecipients;

    /**
     * Create a new event instance.
     *
     * @param Message $originalMessage
     * @param array $standardRecipients
     * @param array $ccRecipients
     * @param array $bccRecipients
     * @param string $subject
     * @param string $body
     */
    public function __construct(Message $originalMessage, array $standardRecipients, array $ccRecipients, array $bccRecipients, $subject, $body)
    {
        $this->threadMessages = $originalMessage->thread()->get();
        $this->subject = $subject;
        $this->body = $body;
        $this->standardRecipients = $standardRecipients;
        $this->ccRecipients = $ccRecipients;
        $this->bccRecipients = $bccRecipients;
    }

}
