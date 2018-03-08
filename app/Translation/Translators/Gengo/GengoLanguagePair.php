<?php


namespace App\Translation\Translators\Gengo;


class GengoLanguagePair
{
    /**
     * Language pairs returned by Gengo.
     *
     * The source language is already set.
     *
     * @var array
     */
    protected $pairs;

    /**
     * GengoLanguagePair constructor.
     * @param $pairs
     */
    public function __construct($pairs)
    {
        $this->pairs = $pairs;
    }

    /**
     * Filters by translation tier.
     *
     * @param string $tier
     * @return GengoLanguagePair
     */
    public function filterTier($tier)
    {
        $this->pairs = array_filter($this->pairs, function ($pair) use ($tier) {
            return $pair["tier"] == $tier;
        });
        return $this;
    }

    /**
     * Filter pairs by target language.
     *
     * @param $langTgt
     * @return GengoLanguagePair
     */
    public function filterTargetLanguage($langTgt = null)
    {
        if ($langTgt) {
            $this->pairs = array_filter($this->pairs, function ($pair) use ($langTgt) {
                return $pair["lc_tgt"] == $langTgt;
            });
        }
        return $this;
    }

    /**
     * Returns language pairs array.
     *
     * @return array
     */
    public function result()
    {
        return $this->pairs;
    }
}