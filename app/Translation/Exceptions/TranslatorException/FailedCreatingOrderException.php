<?php


namespace App\Translation\Exceptions\TranslatorException;


use App\Translation\Exceptions\TranslatorException;

class FailedCreatingOrderException extends TranslatorException
{
    public function report()
    {
        \Log::error('FAILED_CREATING_ORDER_MODEL', [
            'code' => $this->code,
            'message' => $this->message,
            'exception' => $this
        ]);
    }
}