@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header', ['hash' => $translatedMessage->hash])
			We've translated your message.
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			Your message has been translated and as you have selected the 'Send-to-Self' option, your message was not automatically sent to any recipients and is attached below.
		@endcomponent
	@endcomponent
	@include('emails.messages.html.partials.thread')
@endcomponent

