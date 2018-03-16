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