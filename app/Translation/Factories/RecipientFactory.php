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
     * Create new RecipientFactory instance.
     *
     * @param Message $message
     * @param RecipientType $type
     * @param $email
     */
    public function __construct(Message $message, RecipientType $type, $email)
    {
        $this->message = $message;
        $this->type = $type;
        $this->email = $email;
    }

    /**
     * Store Recipient info in db.
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function createModel()
    {
        // TODO ::: Store recipient names too.
        return Recipient::create([
            'recipient_type_id' => $this->type ? $this->type->id : RecipientType::standard()->id,
            'message_id' => $this->message->id,
            'email' => $this->email
        ]);
    }

    /**
     * Make Recipient.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function make()
    {
        return $this->createModel();
    }

}