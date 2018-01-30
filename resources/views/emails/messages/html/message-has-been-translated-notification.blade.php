@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header', ['hash' => $translatedMessage->hash])
			Your message has been translated and sent.
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			Below, we have included your message along with the translation as your recipients will see it. Thank you for using our services and if you have any suggestions or questions, feel free to reply directly to this email.
		@endcomponent
	@endcomponent
	@include('emails.messages.html.partials.thread')
@endcomponent