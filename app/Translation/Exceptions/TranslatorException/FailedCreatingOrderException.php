<?php


namespace App\Translation\Exceptions\TranslatorException;


use App\Translation\Exceptions\TranslatorException;

class FailedCreatingOrderException extends TranslatorException
{
    public function report()
    {
        parent::report();
        \Log::error('Failed Creating Order', [
            'exception' => $this
        ]);
    }
}