<?php


namespace App\Translation\Translators\Gengo;


class GengoErrorResponse
{
    /**
     * Error body from Gengo API response.
     *
     * @var
     */
    protected $error;

    /**
     * GengoErrorResponse constructor.
     *
     * @param $error
     */
    public function __construct($error)
    {
        $this->error = $error;
    }

    /**
     * Is Job Error?
     *
     * Error could be due to the job (ie. unsupported language pair) or
     * gengo system (not enough Gengo credits). Either case, this
     * is a system error on our part.
     *
     * @return bool
     */
    protected function isJobError()
    {
        return array_key_exists("jobs_01", $this->error);
    }

    /**
     * Error code from Gengo.
     *
     * @return mixed
     */
    public function code()
    {
        if ($this->isJobError()) {
            return $this->error["jobs_01"][0]["code"];
        }
        return $this->error["code"];
    }

    /**
     * Description from Gengo.
     *
     * @return mixed
     */
    public function description()
    {
        if($this->isJobError()) {
            return $this->error["jobs_01"][0]["msg"];
        }
        return $this->error["msg"];
    }

}