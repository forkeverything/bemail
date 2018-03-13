<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAccountRequest;
use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User Settings Page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSettings()
    {
        return view('settings', [
            'user' => Auth::user(),
            'languages' => Language::all()
        ]);
    }

    /**
     * Save User Account settings.
     *
     * @param UpdateAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postUpdateSettings(UpdateAccountRequest $request)
    {

        Auth::user()->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'language_id' => Language::findByCode($request->input('lang_default'))->id
        ]);

        if ($newPassword = $request->input('pwd_new')) {
            Auth::user()->update([
                'password' => bcrypt($newPassword)
            ]);
        }

        flash()->success('Successfully updated your settings!');

        return redirect()->back();
    }   
}
