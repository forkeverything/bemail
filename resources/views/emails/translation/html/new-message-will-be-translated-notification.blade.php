@component('emails.layout.html.main')
		@component('emails.layout.html.main.header', ['hash' => $message->hash])
			Received request to translate message.
		@endcomponent
		@component('emails.layout.html.main.body')
			Messages usually take between 1-2 hours to be translated and sent. Once your message has
			been sent, you will receive another confirmation email from us.
			<br>
			If you would like to check the current status of your message, you can reply to this
			email and ask us for an update anytime.
		@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent