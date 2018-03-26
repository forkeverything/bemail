@component('emails.messages.text.partials.layout')

	Your message could not be translated.

	We're sorry to say we could not translate the following message due to a system error.

	"{{ $message->body  }}"

	We have already been notified and will be working as fast as we can to fix the issue. Please try again later or get in touch for an update.

	Customer Support,
	bemail

	@include('emails.messages.text.partials.thread')
@endcomponent