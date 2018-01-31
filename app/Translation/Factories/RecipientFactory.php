<?php


namespace App\Translation\Factories;


use App\Translation\Message;
use App\Translation\Recipient;
use App\Translation\RecipientType;

class RecipientFactory
{
    /**
     * What kind of recipient?
     * Corresponds to: (to/cc/bcc) fields of an email. If this
     * isn't specified we'll just use standard - ie. the a
     * 'to' email address.
     *
     * @var RecipientType
     */
    private $type;

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
     * Set type.
     *
     * @param RecipientType $type
     * @return $this
     */
    public function type(RecipientType $type) {
        $this->type = $type;
        return $this;
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

        // TODO ::: Store recipient names too.

        return Recipient::create([
            'recipient_type_id' => $this->type ? $this->type->id : RecipientType::standard()->id,
            'message_id' => $this->message->id,
            'email' => $this->email
        ]);
    }

}