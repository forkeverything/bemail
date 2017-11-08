Hi,
<br>
<br>
This email is to confirm that we've received your request to translate a message.
<br>
<br>
Messages usually take between 1-2 hours to be translated and sent. Once your message has been sent, you will receive another confirmation email from us. <strong>If you do not receive a confirmation email within 4 hours, please reply to this email to get a status update for your message.</strong>
<br>
@include('emails.translation.partials.details', ['message' => $translationMessage])
<br>
<!-- TODO ::: Uncomment after charging user has been implemented -->
{{--<h3>Payment</h3>--}}
{{--<strong>Unit Price:</strong> $ {{ $translationMessage->receipt->cost_per_word/100 }}/word--}}
{{--<br>--}}
{{--<strong>Credits Used:</strong> {{ $translationMessage->receipt->creditTransaction->amount }}--}}
{{--<br>--}}
{{--<strong>Amount Charge:</strong> $ {{ $translationMessage->receipt->amount_charged/100 }}--}}
{{--<br>--}}
<h3>Message</h3>
{{ $translationMessage->body }}
