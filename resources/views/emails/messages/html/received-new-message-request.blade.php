@component('emails.messages.html.layout')
	@component('emails.messages.html.main')
		@component('emails.messages.html.main.header', ['hash' => $translationMessage->hash])
			Received request to translate message.
		@endcomponent
		@component('emails.messages.html.main.body')
			Messages usually take between 1-2 hours to be translated and sent. Once your message has
			been sent, you will receive another confirmation email from us.
			<br>
			If you would like to check the current status of your message, you can reply to this
			email and ask us for an update anytime.
		@endcomponent
	@endcomponent
	@include('emails.messages.html.thread')
@endcomponent