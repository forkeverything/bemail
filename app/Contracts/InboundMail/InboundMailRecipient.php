<?php

namespace App\Contracts\InboundMail;

interface InboundMailRecipient
{
    /**
     * The recipient's email.
     *
     * @return string
     */
    public function email();

    /**
     * The recipient's name.
     *
     * @return string
     */
    public function name();
}