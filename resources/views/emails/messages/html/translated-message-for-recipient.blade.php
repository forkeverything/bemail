@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header', ['hash' => $translatedMessage->hash])
			Translated message from {{ $translatedMessage->senderName() }} ({{ $translatedMessage->senderEmail() }})
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			<strong>How to reply</strong>
			<br>
			1. Translate - Reply to this email as usual. Your message will automatically be translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.
			<br>
			2. Do Not Translate - Send a new email. We will not receive your eamil and there will be no fees involved.
			<br>
		@endcomponent
	@endcomponent
	@include('emails.messages.html.partials.thread')
@endcomponent

