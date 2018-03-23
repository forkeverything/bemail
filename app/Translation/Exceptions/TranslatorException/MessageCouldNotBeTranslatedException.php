<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

/**
 * Class MessageCouldNotBeTranslatedException
 *
 * @package App\Translation\Exceptions
 */
class MessageCouldNotBeTranslatedException extends TranslatorException
{
    public function report()
    {
        parent::report();
        \Log::error('Could Not Cancel Translation Job', [
            'exception' => $this
        ]);
    }
}