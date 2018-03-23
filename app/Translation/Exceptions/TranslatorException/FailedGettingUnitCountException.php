<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

class FailedGettingUnitCountException extends TranslatorException
{
    public function report()
    {
        parent::report();
        \Log::error('Failed Getting Unit Count For Message', [
            'exception' => $this
        ]);
    }

}