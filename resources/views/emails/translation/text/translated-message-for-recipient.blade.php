@component('emails.layout.text.main')

	Message: {{ $translatedMessage->hash }}
	Translated message from {{ $translatedMessage->sender_name }} ({{ $translatedMessage->sender_email }})

	# How to reply
	1. Translate - Reply to this email as usual. Your message will automatically be translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.
	2. Do Not Translate - Send a new email. We will not receive your eamil and there will be no fees involved.

	@include('emails.layout.text.message-thread')
@endcomponent