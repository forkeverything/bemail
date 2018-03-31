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
     * The reply sender's email.
     *
     * @var string
     */
    public $senderEmail;
    /**
     * Message that was being replied to.
     *
     * @var Message
     */
    public $originalMessage;
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
     * The email subject.
     *
     * @var string
     */
    public $replySubject;
    /**
     * The email body.
     *
     * @var string
     */
    public $replyBody;

    /**
     * Create a new event instance.
     *
     * @param $senderEmail
     * @param Message $originalMessage
     * @param array $standardRecipients
     * @param array $ccRecipients
     * @param array $bccRecipients
     * @param $replySubject
     * @param $replyBody
     */
    public function __construct($senderEmail, Message $originalMessage, array $standardRecipients, array $ccRecipients, array $bccRecipients, $replySubject, $replyBody)
    {
        $this->senderEmail = $senderEmail;
        $this->originalMessage = $originalMessage;
        $this->standardRecipients = $standardRecipients;
        $this->ccRecipients = $ccRecipients;
        $this->bccRecipients = $bccRecipients;
        $this->replySubject = $replySubject;
        $this->replyBody = $replyBody;
    }

}
