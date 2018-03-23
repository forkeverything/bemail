<?php

namespace App\Translation\Exceptions;

class CouldNotCancelTranslationException extends TranslatorException
{

    public function report()
    {
        parent::report();
        \Log::error('Could Not Cancel Translation Job', [
            'exception' => $this
        ]);
    }

}