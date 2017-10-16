<?php

namespace App\Contracts\Translation;

use App\Message;

interface Translator
{
    public function translate(Message $message);
}