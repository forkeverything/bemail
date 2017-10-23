Hi,
<br>
<br>
This email is to confirm that we've completed translating your email and it has been sent to it's recipient(s).
<br>
@include('emails.translation.partials.details', ['message' => $translatedMessage])
<br>
<h3>Original Message</h3>
{{ $translatedMessage->body }}
<br>
<h3>Translated Message</h3>
{{ $translatedMessage->translated_body }}