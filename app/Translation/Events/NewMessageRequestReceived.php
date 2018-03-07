<?php

namespace App\Translation\Events;

use App\Http\Requests\CreateMessageRequest;
use App\Translation\Contracts\Translator;
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
     * @var Translator
     */
    public $translator;
    /**
     * @var Message
     */
    public $message;
    /**
     * @var User
     */
    public $user;
    /**
     * @var string
     */
    public $subject;
    /**
     * @var string
     */
    public $body;
    /**
     * @var bool
     */
    public $autoTranslateReply;
    /**
     * @var bool
     */
    public $sendToSelf;
    /**
     * @var int
     */
    public $langSrcId;
    /**
     * @var int
     */
    public $langTgtId;
    /**
     * @var array
     */
    public $recipientEmails;
    /**
     * @var array
     */
    public $attachments;

    /**
     * NewMessageRequestReceived constructor.
     * @param string $subject
     * @param string $body
     * @param bool $autoTranslateReply
     * @param bool $sendToSelf
     * @param int $langSrcId
     * @param int $langTgtId
     * @param array $recipientEmails
     * @param array $attachments
     * @param User $user
     * @param Translator $translator
     */
    public function __construct(string $subject, string $body, bool $autoTranslateReply, bool $sendToSelf, int $langSrcId, int $langTgtId, array $recipientEmails, array $attachments = [], User $user, Translator $translator)
    {
        $this->user = $user;
        $this->subject = $subject;
        $this->body = $body;
        $this->autoTranslateReply = $autoTranslateReply;
        $this->sendToSelf = $sendToSelf;
        $this->langSrcId = $langSrcId;
        $this->langTgtId = $langTgtId;
        $this->recipientEmails = $recipientEmails;
        $this->attachments = $attachments;
        $this->translator = $translator;
    }
}
