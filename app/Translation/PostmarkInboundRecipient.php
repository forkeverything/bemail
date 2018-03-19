<?php


namespace App\Translation;


class PostmarkInboundRecipient
{
    /**
     * @var string
     */
    private $json;

    /**
     * Create PostmarkInboundRecipient instance.
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