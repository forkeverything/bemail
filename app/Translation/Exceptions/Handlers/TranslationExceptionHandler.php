<?php


namespace App\Translation\Exceptions\Handlers;


use App\Translation\Exceptions\TranslationException;
use App\Translation\Mail\SystemTranslationError;
use App\Translation\Message;
use App\Translation\MessageError;
use App\Translation\TranslationStatus;
use App\User;
use Illuminate\Support\Facades\Mail;

class TranslationExceptionHandler
{
    /**
     * Exception thrown.
     *
     * @var
     */
    private $exception;

    /**
     * The Message that could not be translated.
     *
     * @var Message
     */
    private $message;

    /**
     * The exception that was thrown.
     *
     * @param TranslationException $exception
     * @return static
     */
    public static function got(TranslationException $exception)
    {
        $handler = new static();
        $handler->exception = $exception;
        return $handler;
    }

    /**
     * Message it was thrown for.
     *
     * @param Message $message
     * @return $this
     */
    public function for(Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Main handle method
     *
     */
    public function handle()
    {
        $this->updateMessageStatus()
             ->recordError()
             ->notifySystem();
    }

    /**
     * Set Message status: Error.
     *
     * @return $this
     */
    protected function updateMessageStatus()
    {
        $this->message->updateStatus(TranslationStatus::error());
        return $this;
    }

    /**
     * Record the MessageError in DB.
     *
     * @return $this
     */
    protected function recordError()
    {
        MessageError::create([
            'code' => $this->exception->getCode(),
            'description' => $this->exception->getMessage(),
            'message_id' => $this->message->id
        ]);
        return $this;
    }

    /**
     * Let sys admins know of failure.
     *
     * @return $this
     */
    protected function notifySystem()
    {
        // Notify admin of system error resulting in failure to translate
        Mail::to(User::where('email', 'mike@bemail.io')->first())->send(new SystemTranslationError($this->message));

        // TODO ::: Create different user types (ie. admin / system manager etc.) and notify relevant User(s).
        // TODO ::: Send notifications instead of emails if there are multiple channels.

        return $this;
    }
}