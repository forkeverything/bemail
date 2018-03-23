<?php


namespace App\InboundMail\Postmark;


use Exception;

trait Reportable
{
    /**
     * Manually log Exception.
     *
     * @param Exception $e
     */
    protected function reportException(Exception $e)
    {
        \Log::error('POSTMARK INBOUND MAIL EXCEPTION', [
            'exception' => $e
        ]);
    }
}