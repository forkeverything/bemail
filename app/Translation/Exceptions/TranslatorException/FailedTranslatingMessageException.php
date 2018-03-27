<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

/**
 * Class FailedTranslatingMessageException
 *
 * @package App\Translation\Exceptions
 */
class FailedTranslatingMessageException extends TranslatorException
{
    public function report()
    {
        \Log::error('FAILED_TRANSLATING_MESSAGE', [
            'code' => $this->code,
            'message' => $this->message,
            'exception' => $this
        ]);
    }
}