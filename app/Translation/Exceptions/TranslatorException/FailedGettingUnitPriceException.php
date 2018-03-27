<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

class FailedGettingUnitPriceException extends TranslatorException
{
    public function report()
    {
        \Log::error('FAILED_GETTING_UNIT_PRICE', [
            'code' => $this->code,
            'message' => $this->message,
            'exception' => $this
        ]);
    }

}