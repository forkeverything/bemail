<?php

namespace App\Translation\Mail;

use App\Translation\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyNotSentDueToSystemErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Message thread being replied to.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $threadMessages;
    /**
     * @var array
     */
    public $standardRecipients;
    /**
     * @var array
     */
    public $ccRecipients;
    /**
     * @var array
     */
    public $bccRecipients;
    /**
     * @var string
     */
    public $replySubject;
    /**
     * @var string
     */
    public $replyBody;

    /**
     * Create a new message instance.
     *
     * @param Message $originalMessage
     * @param array $standardRecipients
     * @param array $ccRecipients
     * @param array $bccRecipients
     * @param $replySubject
     * @param $replyBody
     */
    public function __construct(Message $originalMessage, array $standardRecipients, array $ccRecipients, array $bccRecipients, $replySubject, $replyBody)
    {
        $this->threadMessages = $originalMessage->thread()->get();
        $this->standardRecipients = $standardRecipients;
        $this->ccRecipients = $ccRecipients;
        $this->bccRecipients = $bccRecipients;
        $this->replySubject = $replySubject;
        $this->replyBody = $replyBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('REPLY ERROR: ' . $this->replySubject)
                    ->view('emails.translation.html.reply-not-sent-due-to-system-error-notification')
                    ->text('emails.translation.html.reply-not-sent-due-to-system-error-notification');
    }
}
