@component('emails.layout.text.main')

	Message: {{ $message->hash }}
	Received reply to message.

	We've received your reply to a translated message. We will automatically translate your reply and send it out to all recipients when complete. Translations usually take between 1-2 hours.
	If you would like to check the current status of your message, you can reply to this
	email and ask us for an update anytime.

	@include('emails.layout.text.message-thread')
@endcomponent