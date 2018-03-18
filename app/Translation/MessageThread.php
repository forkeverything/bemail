<?php


namespace App\Translation;


use Illuminate\Database\Eloquent\Collection;

class MessageThread
{

    /**
     * Current Message in the loop.
     *
     * @var Message
     */
    private $currentMessage;

    /**
     * Message thread in a Collection.
     *
     * @var Collection
     */
    private $thread;

    /**
     * Create MessageThread starting from a given Message.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->currentMessage = $message;
        $this->thread = new Collection();
        $this->build();
    }

    /**
     * Add current Message to thread.
     */
    private function addCurrentMessage()
    {
        $this->thread->push($this->currentMessage);
    }

    /**
     * All the Message(s) that are replying to the same Message.
     *
     * @return Collection|static[]
     */
    private function currentMessageSiblings()
    {
        // Get replies to the same Message
        return Message::where('message_id', $this->currentMessage->message_id)
                      // Ignore current message (already added to thread)
                      ->where('id', '!=', $this->currentMessage->id)
                      // Latest first
                      ->orderBy('created_at', 'desc')
                      ->get();
    }

    /**
     * Add siblings to thread.
     */
    private function addSiblings()
    {
        // Push onto thread. Use push instead of merge
        // to ensure message order.
        foreach ($this->currentMessageSiblings() as $sibling) {
            $this->thread->push($sibling);
        }
    }

    /**
     * Gets the Next
     */
    private function setNextMessage()
    {
        $this->currentMessage = $this->currentMessage->originalMessage;
    }

    /**
     * Recursively build thread.
     */
    private function build()
    {
        $this->addCurrentMessage();
        if ($this->currentMessage->isReply()) {
            $this->addSiblings();
            $this->setNextMessage();
            $this->build();
        }
    }

    /**
     * Return the actual thread.
     *
     * @return Collection
     */
    public function get()
    {
        return $this->thread;
    }

}