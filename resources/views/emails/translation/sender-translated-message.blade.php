@component('mail::message')
<sub>Message: {{  $translatedMessage->hash }}</sub>
# Your Message Has Been Translated

We have completed translating your message. As you have selected the 'Send-to-Self' option, your message was not automatically sent to any recipients and is attached below.

## Translated Message

"{{ $translatedMessage->translated_body }}"

## Original Message

"{{ $translatedMessage->body }}"
@endcomponent