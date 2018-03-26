@component('emails.layout.text.main')
	Sorry, we couldn't translate your reply.

	We've recently received the following message from you:

	"{{ $message->body }}"

	Unfortunately, as we couldn't charge {{ $message->owner->name }} ({{ $message->owner->email }}) for the translation fees, we were unable to complete the translation. Your message was still sent in {{ $message->sourceLanguage->name }}.

	@include('emails.layout.text.message-thread')
@endcomponent