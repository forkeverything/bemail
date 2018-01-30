@component('emails.messages.text.partials.layout')

	Message: {{ $translatedMessage->hash }}
	Translated message from {{ $translatedMessage->senderName() }} ({{ $translatedMessage->senderEmail() }})

	# How to reply
	1. Translate - Reply to this email as usual. Your message will automatically be translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.
	2. Do Not Translate - Send a new email. We will not receive your eamil and there will be no fees involved.

	@include('emails.messages.text.partials.thread')
@endcomponent