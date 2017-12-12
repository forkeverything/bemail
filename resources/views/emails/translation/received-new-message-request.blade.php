@component('mail::message')
# We've Received Your Request To Translate An Email

Messages usually take between 1-2 hours to be translated and sent. Once your message has been sent, you will receive another confirmation email from us. **If you do not receive a confirmation email within 4 hours, please reply to this email to get a status update for your message.**

## Original Message

{{ $translationMessage->body }}
@endcomponent

