<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{

    public function postFetchAllLanguages()
    {
        return Language::all();
    }
}

