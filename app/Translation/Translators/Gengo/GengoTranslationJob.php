<?php


namespace App\Translation\Translators\Gengo;


use App\Language;
use App\Translation\Message;

class GengoTranslationJob
{
    /**
     * Custom identifier from Gengo dashboard.
     *
     * @var null|string
     */
    protected $slug;
    /**
     * Text to be translated.
     *
     * @var string
     */
    protected $body;
    /**
     * Language to translate from.
     *
     * @var int
     */
    protected $sourceLanguage;
    /**
     * Language to translate to.
     *
     * @var int
     */
    protected $targetLanguage;
    /**
     * JSON data that will be included with the job (max 1KB).
     *
     * @var null|string
     */
    protected $customData;
    /**
     * Job data array to be returned.
     *
     * @var array
     */
    protected $job = [];

    /**
     * Create a Gengo Job for given Message.
     *
     * @param Message $message
     * @return $this
     */
    public static function forMessage(Message $message)
    {
        $job = new static();

        $job->slug($message->hash)
            ->body($message->body)
            ->languages($message->sourceLanguage->code, $message->targetLanguage->code)
            ->customData("{\"message_hash\": \"{$message->hash}\"}");
        return $job;
    }

    /**
     * Create a new Gengo Job to get a quote from Gengo.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @param $text
     * @return static
     */
    public static function forQuote(Language $sourceLanguage, Language $targetLanguage, $text)
    {
        $job = new static();
        $job->languages($sourceLanguage->code, $targetLanguage->code)
            ->body($text);
        return $job;
    }

    /**
     * Slug that makes jobs identifiable from Gengo dashboard.
     *
     * @param string $identifier
     * @return $this
     */
    protected function slug($identifier)
    {
        $this->slug = $identifier;
        return $this;
    }

    /**
     * Message body to be translated.
     *
     * @param $text
     * @return $this
     */
    protected function body($text)
    {
        $this->body = $text;
        return $this;
    }

    /**
     * Language to translate from and to.
     *
     * @param $srcLangCode
     * @param $tgtLangCode
     * @return $this
     */
    protected function languages($srcLangCode, $tgtLangCode)
    {
        $this->sourceLanguage = $srcLangCode;
        $this->targetLanguage = $tgtLangCode;
        return $this;
    }

    /**
     * Set custom data field.
     *
     * @param $string
     * @return $this
     */
    protected function customData($string)
    {
        $this->customData = $string;
        return $this;
    }

    /**
     * Build and return Gengo required array.
     *
     * @return array
     */
    public function build()
    {

        $this->addDefaultFields()
             ->addSlug()
             ->addBody()
             ->addLanguages()
             ->addCustomData();

        return [
            "job_01" => $this->job
        ];
    }

    /**
     * Fields that are the same for each job.
     *
     * @return $this
     */
    protected function addDefaultFields()
    {
        $this->job['type'] = 'text';
        // Gengo's defined 'level / quality' of translation.
        $this->job['tier'] = 'standard';
        $this->job['callback_url'] = env('GENGO_CALLBACK_URL');
        $this->job['auto_approve'] = 1;
        $this->job['force'] = 0;
        $this->job['use_preferred'] = 0;
        return $this;
    }

    /**
     * Add slug to job data.
     *
     * @return $this
     */
    protected function addSlug()
    {
        if (isset($this->slug)) {
            $this->job['slug'] = $this->slug;
        }
        return $this;
    }

    /**
     * Add body to job data.
     *
     * @return $this
     */
    protected function addBody()
    {
        $this->job['body_src'] = $this->body;
        return $this;
    }

    /**
     * Add language codes to job data.
     *
     * @return $this
     */
    protected function addLanguages()
    {
        $this->job['lc_src'] = $this->sourceLanguage;
        $this->job['lc_tgt'] = $this->targetLanguage;
        return $this;
    }

    /**
     * Add custom data to job data.
     *
     * @return $this
     */
    protected function addCustomData()
    {
        if(isset($this->customData)) {
            $this->job['custom_data'] = $this->customData;
        }
        return $this;
    }

}