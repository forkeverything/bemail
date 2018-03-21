<?php

namespace App\InboundMail\Postmark;

use App\Contracts\InboundMail\InboundMailRecipient;

class PostmarkInboundMailRecipient implements InboundMailRecipient
{
    /**
     * @var string
     */
    private $json;

    /**
     * Create PostmarkInboundMailRecipient instance.
     *
     * @param $json
     */
    public function __construct($json)
    {
        $this->json = json_decode($json, true);
    }

    /**
     * The recipient's email.
     *
     * @return string
     */
    public function email()
    {
        return $this->json["Email"];
    }

    /**
     * The recipient's name.
     *
     * @return string
     */
    public function name()
    {
        return $this->json["Name"];
    }
}