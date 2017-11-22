@component('mail::message')
# Translation Request Received
Just confirming that we've received your request to translate an email.

Messages usually take between 1-2 hours to be translated and sent. Once your message has been sent, you will receive another confirmation email from us. **If you do not receive a confirmation email within 4 hours, please reply to this email to get a status update for your message.**

## Original Message

{{ $translationMessage->body }}
@endcomponent

