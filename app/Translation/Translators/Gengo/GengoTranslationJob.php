<?php


namespace App\Translation\Translators\Gengo;


use App\Translation\Message;

class GengoTranslationJob
{

    /**
     * Message to be translated.
     *
     * @var Message
     */
    protected $message;
    /**
     * @var string
     */
    protected $slug;
    /**
     * @var string
     */
    protected $body;
    /**
     * @var int
     */
    protected $sourceLanguage;
    /**
     * @var int
     */
    protected $targetLanguage;
    /**
     * @var string
     */
    protected $tier;
    /**
     * @var string
     */
    protected $callbackUrl;
    /**
     * @var string
     */
    protected $messageHash;

    /**
     * GengoTranslationJob constructor.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;

        $this->setSlug()
             ->setBody()
             ->setLanguages()
             ->setTier()
             ->setCallbackUrl()
             ->setMessageHash();
    }

    /**
     * Slug that makes jobs identifiable from Gengo dashboard.
     *
     * @return $this
     */
    protected function setSlug()
    {
        $this->slug = $this->message->hash;
        return $this;
    }

    /**
     * Message body to be translated.
     *
     * @return $this
     */
    protected function setBody()
    {
        $this->body = $this->message->body;
        return $this;
    }

    /**
     * Set the language to translate from and to.
     *
     * @return $this
     */
    protected function setLanguages()
    {
        $this->sourceLanguage = $this->message->sourceLanguage->code;
        $this->targetLanguage = $this->message->targetLanguage->code;
        return $this;
    }

    /**
     * Gengo tier (translation quality level).
     *
     * @return $this
     */
    protected function setTier()
    {
        $this->tier = "standard";
        return $this;
    }

    /**
     * Callback URL that Gengo will use.
     *
     * @return $this
     */
    protected function setCallbackUrl()
    {
        $this->callbackUrl = env('GENGO_CALLBACK_URL');
        return $this;
    }

    /**
     * Message hash.
     *
     * @return $this
     */
    protected function setMessageHash()
    {
        $this->messageHash = $this->message->hash;
        return $this;
    }

    /**
     * Build and return Gengo required array.
     *
     * @return array
     */
    public function build()
    {
        return [
            "jobs_01" => [
                'type' => 'text',
                'slug' => $this->slug,
                'body_src' => $this->body,
                'lc_src' => $this->sourceLanguage,
                'lc_tgt' => $this->targetLanguage,
                'tier' => $this->tier,
                'auto_approve' => 1,
                'force' => 0,
                'callback_url' => $this->callbackUrl,
                'custom_data' => "{
                    \"message_hash\": \"{$this->messageHash}\"
                }",
                'use_preferred' => 0
            ]
        ];
    }

}