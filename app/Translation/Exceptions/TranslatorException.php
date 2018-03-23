<?php


namespace App\Translation\Exceptions;


class TranslatorException extends \Exception
{
    public function report()
    {
        \Log::error('Translator Error Occurred', [
            'exception' => $this
        ]);
    }
}