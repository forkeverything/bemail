<?php

namespace App\Contracts\Translation;

use App\Message;

interface Translator
{
    /**
     * Translate a Message.
     *
     * @param Message $message
     * @return mixed
     */
    public function translate(Message $message);
}