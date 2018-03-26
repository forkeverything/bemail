@component('emails.layout.html.main')
	@component('emails.layout.html.main.header', ['hash' => $translatedMessage->hash])
		We've translated your message.
	@endcomponent
	@component('emails.layout.html.main.body')
		Your message has been translated and as you have selected the 'Send-to-Self' option, your message was not automatically sent to any recipients and is attached below.
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent

