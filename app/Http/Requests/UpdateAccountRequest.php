<?php

namespace App\Http\Requests;

use App\Rules\CurrentUserPassword;
use App\Rules\LanguageCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // All User(s) are allowed to update
        // their account settings.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @noinspection PhpUndefinedClassInspection */
        $rules = [
                'name' => 'required|string|max:255',
                'email' => ['required','string','email','max:255', Rule::unique('users')->ignore(Auth::id())],
                'lang_default' => ['required', new LanguageCode]
        ];

        $currentPassword = $this->input('pwd_current');
        $newPassword = $this->input('pwd_new');
        $newPasswordConfirmation = $this->input('pwd_new_confirmation');

        if ($currentPassword || $newPassword || $newPasswordConfirmation) {
            $rules["pwd_current"] = ['required', 'string', new CurrentUserPassword];
            $rules["pwd_new"] = "required|string|min:6|confirmed";
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'pwd_current.required' => 'Current password cannot be blank.',
            'pwd_current.string' => 'Password contains invalid characters',
            'pwd_new.required' => 'Password cannot be blank.',
            'pwd_new.string' => 'Password contains invalid characters.',
            'pwd_new.min' => 'Password must longer than 6 characters.',
            'pwd_new.confirmed' => 'Password confirmation did not match.'
        ];
    }
}
