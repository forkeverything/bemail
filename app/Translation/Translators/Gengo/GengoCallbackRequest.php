<?php

namespace App\Translation\Translators\Gengo;

use Illuminate\Http\Request;

/**
 * Class GengoCallbackRequest
 * @package App\Translation
 */
class GengoCallbackRequest
{

    /**
     * @var Request
     */
    private $request;

    /**
     * Create GengoCallbackRequest instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Related to a Gengo translation job (ie. not comment).
     *
     * @return bool
     */
    public function isJobRequest()
    {
        return array_key_exists("job", $this->request->all());
    }

    /**
     * The request body.
     *
     * Gengo stores these inside a 'job' key.
     *
     * @return mixed
     */
    private function body()
    {
        return json_decode($this->request->all()["job"], true);
    }

    /**
     * The message identifier that was sent with the translation job.
     *
     * @return mixed
     */
    public function messageHash()
    {
        $customData = json_decode($this->body()["custom_data"], true);
        return $customData["message_hash"];
    }

    /**
     * The message status.
     *
     * @return mixed
     */
    public function status()
    {
        return $this->body()["status"];
    }

    /**
     * The translated text.
     *
     * @return mixed
     */
    public function translatedBody()
    {
        return $this->body()["body_tgt"];
    }

}