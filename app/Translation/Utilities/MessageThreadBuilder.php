<?php


namespace App\Translation\Utilities;


use App\Translation\Message;
use App\Translation\Reply;
use Illuminate\Database\Eloquent\Collection;

class MessageThreadBuilder
{

    /**
     * Current Message in the loop.
     *
     * @var Message
     */
    private $currentMessage;

    /**
     * Messages thread.
     *
     * @var Collection
     */
    private $thread;

    /**
     * Creates a Message(s) thread starting from given Message.
     *
     * @param Message $message
     * @return mixed
     */
    public static function startingFrom(Message $message)
    {
        $builder = new static($message);
        $builder->build();
        return $builder->thread;
    }

    /**
     * MessageThreadBuilder constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->thread = new Collection();
        $this->currentMessage = $message;
    }

    /**
     * Add siblings (Message(s) that are replies to the
     * same original Message).
     *
     * @return mixed
     */
    public function addSiblings()
    {

        $originalMessageId = $this->currentMessage->parentReplyClass->original_message_id;

        $siblings = Message::join('replies', 'messages.reply_id', '=', 'replies.id')
            // Ignore current message (already added to thread)
                ->where('messages.id', '!=', $this->currentMessage->id)
                // Get replies to the same Message
               ->where('original_message_id', $originalMessageId)
                // Latest first
               ->orderBy('created_at', 'desc')
                // Only return Message fields (ignore Reply fields).
                // 'messages.id as id' is needed because for some
                // reason id is replaced by replies.id
               ->select('messages.*', 'messages.id as id')
               ->get();
        $this->thread = $this->thread->merge($siblings);
    }

    /**
     * Add current Message to thread.
     */
    public function addCurrentMessage()
    {
        $this->thread->push($this->currentMessage);
    }

    /**
     * Gets the Next
     */
    public function setNextMessage()
    {
        $this->currentMessage = $this->currentMessage->parentReplyClass->originalMessage;
    }

    /**
     * Builds current level of Message(s)
     */
    public function build()
    {
        $this->addCurrentMessage();
        if ($this->currentMessage->isReply()) {
            $this->addSiblings();
            $this->setNextMessage();
            $this->build();
        }
    }



}