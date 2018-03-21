<?php

namespace App\Http\Controllers;

use App\Language;
use App\Contracts\Translation\Translator;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Get the unit count for given Language(s) and text.
     *
     * @param Request $request
     * @return int
     */
    public function postUnitCount(Request $request)
    {
        $sourceLanguage = Language::findByCode($request->lang_src);
        $targetLanguage = Language::findByCode($request->lang_tgt);
        $text = $request->input('body');

        return $this->translator->unitCount($sourceLanguage, $targetLanguage, $text);
    }

    /**
     * Get the unit price for given Language(s) and User.
     *
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    public function postUnitPrice(Request $request)
    {
        $sourceLanguage = Language::findByCode($request->lang_src);
        $targetLanguage = Language::findByCode($request->lang_tgt);

        $translator = $this->translator->unitPrice($sourceLanguage, $targetLanguage);
        $bemail = Auth::user()->plan()->surcharge();

        return $translator + $bemail;
    }
}
