@component('emails.layout.text.main')
	Your message was not translated or sent.

	We were unsuccessful in charging your account for the following message:

	"{{ $message->body }}"

	Your message will not be translated or sent.
	If you would still like us to translate it for you, please update your payment details and try again.
@endcomponent