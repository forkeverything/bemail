<?php

namespace App\Contracts\Translation;

use App\Language;
use App\Translation\Exceptions\CouldNotCancelTranslationException;
use App\Translation\Exceptions\TranslatorException\MessageCouldNotBeTranslatedException;
use App\Translation\Message;
use App\Translation\Order;

interface Translator
{
    /**
     * Translate a Message.
     *
     * @param Message $message
     * @return mixed
     * @throws MessageCouldNotBeTranslatedException
     */
    public function translate(Message $message);

    /**
     * The amount of units (words) the translator will charge for.
     *
     * This is different for various languages because for
     * certain languages, multiple characters make up
     * one single word.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @param $text
     * @return int
     */
    public function unitCount(Language $sourceLanguage, Language $targetLanguage, $text);

    /**
     * Gets the cost per word for given language pair.
     *
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     * @return int
     */
    public function unitPrice(Language $sourceLanguage, Language $targetLanguage);

    /**
     * Cancels the translation Order.
     *
     * @param $order
     * @return mixed
     * @throws CouldNotCancelTranslationException
     */
    public function cancelTranslating($order);
}