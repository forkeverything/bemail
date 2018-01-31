@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header', ['hash' => $translationMessage->hash])
			Received reply to message.
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			We've received your reply to a translated message. We will automatically translate your reply and send it out to all recipients when complete. Translations usually take between 1-2 hours.
			<br>
			If you would like to check the current status of your message, you can reply to this
			email and ask us for an update anytime.
		@endcomponent
	@endcomponent
	@include('emails.messages.html.partials.thread')
@endcomponent