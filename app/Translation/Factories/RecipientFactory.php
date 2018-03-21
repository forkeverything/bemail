<?php

namespace App\Translation\Factories;

use App\Translation\Factories\RecipientFactory\RecipientEmails;
use App\Translation\Message;
use App\Translation\Recipient;
use App\Translation\Recipient\RecipientType;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class RecipientFactory
 *
 * @package App\Translation\Factories
 */
class RecipientFactory
{

    /**
     * Message that is intended for this PostmarkInboundMailRecipient.
     *
     * @var Message
     */
    private $message;

    /**
     * Email addresses to create as PostmarkInboundMailRecipient(s).
     *
     * @var RecipientEmails
     */
    private $recipientEmails;

    /**
     * Newly created PostmarkInboundMailRecipient(s).
     *
     * @var Collection
     */
    private $recipients;

    /**
     * Create new RecipientFactory instance.
     *
     * @param Message $message
     * @param RecipientType $type
     * @param $email
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->recipients = new Collection();
    }

    /**
     * PostmarkInboundMailRecipient emails are only accepted as a custom class.
     *
     * @param RecipientEmails $recipientEmails
     * @return $recipientEmails|$this
     */
    public function recipientEmails(RecipientEmails $recipientEmails = null)
    {
        if (is_null($recipientEmails)) {
            return $this->recipientEmails;
        }
        $this->recipientEmails = $recipientEmails;
        return $this;
    }

    /**
     * Create and store PostmarkInboundMailRecipient.
     *
     * @param RecipientType $type
     * @param $email
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function createRecipient(RecipientType $type, $email)
    {
        // TODO ::: Store recipient names too.
        return Recipient::create([
            'recipient_type_id' => $type->id,
            'message_id' => $this->message->id,
            'email' => $email
        ]);
    }

    /**
     * Creates multiple PostmarkInboundMailRecipient(s).
     *
     * @return Collection
     */
    public function make()
    {
        foreach ($this->recipientEmails->all() as $type => $emails) {
            $recipientType = RecipientType::whereName($type)->firstOrFail();
            foreach ($emails as $email) {
                $recipient = $this->createRecipient($recipientType, $email);
                $this->recipients->push($recipient);
            }
        }
        return $this->recipients;
    }

}