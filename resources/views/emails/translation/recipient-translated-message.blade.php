@component('mail::message')
<sub>Message: {{  $translatedMessage->hash }}</sub>
# Translated Message From {{ $translatedMessage->user->name }} ({{ $translatedMessage->user->email }})

"{{ $translatedMessage->translated_body }}"

## Original Message

"{{ $translatedMessage->body }}"

@if($translatedMessage->auto_translate_reply)
---
# <center>How To Reply</center>

{{ $translatedMessage->user->name }} has turned on 'Auto-Translate' for replies. This means you can:

1. Reply to this email - Your message will be automatically translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.

2. Send a new email to <a href="mailto:{{ $translatedMessage->user->email }}">{{ $translatedMessage->user->name }}</a> - Your email will **not be translated** and there will be no fees involved.

@endif

@endcomponent