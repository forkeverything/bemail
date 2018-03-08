<?php


namespace App\Translation\Translators\Gengo;


class GengoResponse
{

    /**
     * Response from Gengo as associative array.
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
     * Was Request Successful?
     *
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->response["opstat"] == "ok";
    }

    /**
     * Main Response Body.
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

}