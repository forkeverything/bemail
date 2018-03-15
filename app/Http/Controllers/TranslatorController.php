<?php

namespace App\Http\Controllers;

use App\Language;
use App\Translation\Contracts\Translator;
use Illuminate\Http\Request;

class TranslatorController extends Controller
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function postUnitPrice(Request $request)
    {
        $user = \Auth::user();

        $sourceLanguage = Language::findByCode($request->lang_src);
        $targetLanguage = Language::findByCode($request->lang_tgt);

        $translator = $this->translator->unitPrice($sourceLanguage, $targetLanguage);
        $bemail = $user->plan()->surcharge();


        return $translator + $bemail;
    }
}
