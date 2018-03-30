@component('emails.layout.html.main')
	@component('emails.layout.html.main.header', ['hash' => $message->hash])
		Translated message from {{ $message->sender_name }} ({{ $message->sender_email }})
	@endcomponent
	@component('emails.layout.html.main.body')
		<strong>How to reply</strong>
		<br>
		1. Translate - Reply to this email as usual. Your message will automatically be translated into {{ $message->sourceLanguage->name }} at no cost to you.
		<br>
		2. Do Not Translate - Send a new email. We will not receive your eamil and there will be no fees involved.
		<br>
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent

