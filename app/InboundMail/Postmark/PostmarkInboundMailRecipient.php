<?php

namespace App\InboundMail\Postmark;

use App\Contracts\InboundMail\InboundMailRecipient;

class PostmarkInboundMailRecipient implements InboundMailRecipient
{

    /**
     * Recipient data.
     *
     * @var array
     */
    protected $recipient;

    /**
     * Create PostmarkInboundMailRecipient instance.
     *
     * @param array $recipient
     */
    public function __construct($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * The recipient's email.
     *
     * @return string
     */
    public function email()
    {
        return $this->recipient["Email"];
    }

    /**
     * The recipient's name.
     *
     * @return string
     */
    public function name()
    {
        return $this->recipient["Name"];
    }
}