<?php

namespace App\Http\Requests;

use App\Language;
use App\Rules\LanguageCode;
use App\Rules\ListOfEmails;
use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // All User(s) are allowed to create a Message.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'recipients' => ['required_without:send_to_self', new ListOfEmails],
            'lang_src' => ['required', new LanguageCode],
            'lang_tgt' => ['required', new LanguageCode, 'different:lang_src'],
            'body' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'recipients.required' => 'Need a recipient to send the message to.',
            'lang_src.required' => 'Please select what language your message is written in.',
            'lang_tgt.required' => 'Please select a language to translate into.',
            'lang_tgt.different' => "Can't translate into the same language.",
            'body.required' => 'Message body can\'t be blank'
        ];
    }
}
