<?php

namespace App\Translation\Translators\Gengo;

class GengoResponse
{

    /**
     * GengoResponse from Gengo as associative array.
     *
     * @var array
     */
    private $response;

    public function __construct($jsonResponse)
    {
        $this->response = $this->decodeJsonResponse($jsonResponse);
    }

    /**
     * Decode response.
     *
     * @param $response
     * @return mixed
     */
    protected function decodeJsonResponse($response)
    {
        return json_decode($response, true);
    }

    /**
     * Was PostmarkInboundMailRequest Successful?
     *
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->response["opstat"] == "ok";
    }

    /**
     * Main GengoResponse Body.
     *
     * @return array
     */
    public function body()
    {
        return $this->response["response"];
    }

    /**
     * Error body.
     *
     * @return mixed
     */
    public function error()
    {
        return $this->response["err"];
    }

    /**
     * Gengo order id from response.
     *
     * @return int
     */
    public function orderId()
    {
        return $this->body()["order_id"];
    }

}