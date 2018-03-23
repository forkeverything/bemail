<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

class FailedGettingUnitPriceException extends TranslatorException
{
    public function report()
    {
        parent::report();
        \Log::error('Failed Getting Unit Price For Message', [
            'exception' => $this
        ]);
    }

}