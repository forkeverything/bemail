<?php


namespace App\Translation\Exceptions;


class TranslationException extends \Exception
{

    public function report()
    {

    }

    public function render($request)
    {
        // TODO ::: Handle custom exceptions, specifically
        //          TranslationException, which should
        //          redirect to separate page.
    }
}