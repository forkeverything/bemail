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
        return Recipient::create([
            'message_id' => $this->message->id,
            'email' => $this->email
        ]);
    }

}