<?php


namespace App\Translation\Exceptions;


class TranslationException extends \Exception
{
    public function __construct()
    {
        $message = "Error creating message. MESSAGE WILL NOT BE SENT.";
        parent::__construct($message);
    }
}