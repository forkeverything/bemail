@component('emails.layout.html.main')
	@component('emails.layout.html.main.header')
		Sorry, we couldn't translate your reply.
	@endcomponent
	@component('emails.layout.html.main.body')
		We've recently received the following message from you:
		<br>
		<br>
		<strong>"{{ $message->body }}"</strong>
		<br>
		<br>
		Unfortunately, as we couldn't charge {{ $message->owner->name }} ({{ $message->owner->email }}) for the translation fees, we were unable to complete the translation. Your message was still sent in {{ $message->sourceLanguage->name }}.
		<br>
		<br>
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent