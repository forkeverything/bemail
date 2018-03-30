@component('emails.layout.html.main')
	@component('emails.layout.html.main.header', ['hash' => $message->hash])
		Your message has been translated and sent.
	@endcomponent
	@component('emails.layout.html.main.body')
		Below, we have included your message along with the translation as your recipients will see it. Thank you for using our services and if you have any suggestions or questions, feel free to reply directly to this email.
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent