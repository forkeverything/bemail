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
     * Try to find an existing Recipient.
     * Recipient(s) belong to a given User, so we need to know
     * the sender of the Message.
     *
     * @return mixed
     */
    protected function findExistingRecipient()
    {
        return Recipient::belongingTo($this->message->user)->where('email', $this->email)->first();
    }

    /**
     * Create Recipient model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    protected function createRecipient()
    {
        return $this->message->user->recipients()->create([
            'email' => $this->email
        ]);
    }

    /**
     * Message the Recipient is receiving.
     *
     * @param Message $message
     * @return static
     */
    public static function for(Message $message)
    {
        $factory = new static();
        $factory->message = $message;
        return $factory;
    }

    /**
     * Recipient email.
     *
     * @param $email
     * @return $this
     */
    public function to($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Make Recipient.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function make()
    {
        return $this->findExistingRecipient() ?: $this->createRecipient();
    }

}