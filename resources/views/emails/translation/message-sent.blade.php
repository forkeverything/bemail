@component('mail::message')
# Your Message Has Been Translated And Sent

We have sent your translated message, along with the original, to the recipients.

## Original Message

{{ $translatedMessage->body }}

## Translated Message

{{ $translatedMessage->translated_body }}

@endcomponent