<?php

namespace App\Translation\Exceptions;

class FailedCancellingTranslationException extends TranslatorException
{
    /**
     * Report Exception
     *
     */
    public function report()
    {
        \Log::error('FAILED_CANCELLING_TRANSLATION_JOB', [
            'code' => $this->code,
            'msg' => $this->message,
            'exception' => $this
        ]);
    }
}