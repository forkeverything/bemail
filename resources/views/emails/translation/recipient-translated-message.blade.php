@component('mail::message')
# Translated Message From {{ $translatedMessage->user->name }} ({{ $translatedMessage->user->email }})

{{ $translatedMessage->translated_body }}

## Original Message

{{ $translatedMessage->body }}

@if($translatedMessage->auto_translate_reply)
## How To Reply

{{ $translatedMessage->user->name }} has turned on 'Auto-Translate' for replies. This enables you to:
1. Reply to this Email - Your message will be automatically translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.
2. Send a New Email to <a href="mailto:{{ $translatedMessage->user->email }}">{{ $translatedMessage->user->name }}</a> - Your email will **not be translated** and there will be no fees for you or {{ $translatedMessage->user->name }}.
@endif

@endcomponent