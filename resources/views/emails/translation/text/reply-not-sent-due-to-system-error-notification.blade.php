@component('emails.layout.text.main')

	Sorry, your reply was not sent.

	Due to a system error we couldn't translate or send out the following reply:

	to: @foreach($standardRecipients as $inboundRecipient){{ $inboundRecipient->email() }}@if(!$loop->last), @endif @endforeach
	@if(count($ccRecipients) > 0)
		cc: @foreach($ccRecipients as $inboundRecipient){{ $recipient->email() }}@if(!$loop->last), @endif @endforeach
	@endif
	@if(count($bccRecipients) > 0)
		bcc: @foreach($bccRecipients as $inboundRecipient){{ $recipient->email() }}@if(!$loop->last), @endif @endforeach
	@endif

	"{{ $replyBody }}"

	We have already been notified and will hopefully have a fix within the next hour. Please try again later or get in touch for an update on the fix. Again, we're really sorry about the inconvenience.

	Customer Support,
	bemail

	@include('emails.layout.text.message-thread')
@endcomponent