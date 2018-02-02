<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Contracts\Translator;
use App\Translation\Translators\GengoTranslator;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    /**
     * Unit price per word for translating from source to target language.
     *
     * @param Translator $translator
     * @param $langSrc
     * @param $langTgt
     * @return mixed
     */
    public function getUnitPrice(Translator $translator, $langSrc, $langTgt)
    {
        $sourceLanguage = Language::findByCode($langSrc);
        $targetLanguage = Language::findByCode($langTgt);
        return $translator->unitPrice($sourceLanguage, $targetLanguage);
    }
}
