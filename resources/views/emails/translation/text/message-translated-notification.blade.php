@component('emails.layout.text.main')

	Message: {{ $message->hash }}
	Your message has been translated and sent.

	Below, we have included your message along with the translation as your recipients will see it. Thank you for using our services and if you have any suggestions or questions, feel free to reply directly to this email.

	@include('emails.layout.text.message-thread')
@endcomponent