<?php

namespace App\Translation\Contracts;

use App\Language;
use App\Translation\Exceptions\TranslationException;
use App\Translation\Message;
use App\Translation\Order;

interface Translator
{
    /**
     * Translate a Message.
     *
     * @param Message $message
     * @return mixed
     * @throws TranslationException
     */
    public function translate(Message $message);

    /**
     * The amount of units (words) the translator will charge for.
     *
     * This is different for various languages because for
     * certain languages, multiple characters make up
     * one single word.
     *
     * @param Language $sourceLangue
     * @param Language $targetLanguage
     * @param $text
     * @return mixed
     */
    public function unitCount(Language $sourceLangue, Language $targetLanguage, $text);

    /**
     * Gets the cost per word for given language pair.
     *
     * @param Language $sourceLangue
     * @param Language $targetLanguage
     * @return mixed
     * @throws \Exception
     */
    public function unitPrice(Language $sourceLangue, Language $targetLanguage);

    /**
     * Cancels the translation Order.
     *
     * @param Order $order
     * @return mixed
     */
    public function cancelTranslating(Order $order);
}