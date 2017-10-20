<?php


namespace App\Translation\Factories;


use App\Translation\Message;
use App\Translation\Recipient;

class RecipientFactory
{
    /**
     * Message that is intended for this Recipient.
     *
     * @var Message
     */
    private $message;

    /**
     * Email address of the Recipient.
     *
     * @var
     */
    private $email;

    /**
     * RecipientFactory constructor.
     *
     * @param Message $message
     * @param $email
     */
    public function __construct(Message $message, $email)
    {
        $this->message = $message;
        $this->email = $email;
    }

    /**
     * Try to find an existing Recipient.
     * Recipient(s) belong to a given User, so we need to know
     * the sender of the Message.
     *
     * @return mixed
     */
    protected function findExistingRecipient()
    {
        return Recipient::belongingTo($this->message->sender)->where('email', $this->email)->first();
    }

    /**
     * Create Recipient model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createRecipient()
    {
        return $this->message->sender->recipients()->create([
            'email' => $this->email
        ]);
    }

    /**
     * Make a Recipient
     *
     * @return Recipient
     */
    public function make()
    {
        $recipient = $this->findExistingRecipient();
        if (!$recipient) {
            $recipient = $this->createRecipient();
        }
        return $recipient;
    }


}