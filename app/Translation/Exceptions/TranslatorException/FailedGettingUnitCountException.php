<?php

namespace App\Translation\Exceptions\TranslatorException;

use App\Translation\Exceptions\TranslatorException;

class FailedGettingUnitCountException extends TranslatorException
{
    public function report()
    {
        \Log::error('FAILED_GETTING_UNIT_COUNT', [
            'code' => $this->code,
            'message' => $this->message,
            'exception' => $this
        ]);
    }

}