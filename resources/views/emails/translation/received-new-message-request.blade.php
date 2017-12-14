@component('mail::message')
<sub>Message: {{  $translationMessage->hash }}</sub>
# We've Received Your Request To Translate An Email

Messages usually take between 1-2 hours to be translated and sent. Once your message has been sent, you will receive another confirmation email from us.

If you would like to check the current status of your message, you can reply to this email and ask us for an update anytime.

## Original Message

"{{ $translationMessage->body }}"
@endcomponent

