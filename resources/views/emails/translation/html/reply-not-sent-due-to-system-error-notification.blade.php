@component('emails.layout.html.main')
	@component('emails.layout.html.main.header')
		Sorry, your reply was not sent.
	@endcomponent
	@component('emails.layout.html.main.body')
		Due to a system error we couldn't translate or send out the following reply:
		<br>
		<br>
		to: @foreach($standardRecipients as $inboundRecipient){{ $inboundRecipient->email() }}@if(!$loop->last), @endif @endforeach
		@if(count($ccRecipients) > 0)
			<br>
			cc: @foreach($ccRecipients as $inboundRecipient){{ $recipient->email() }}@if(!$loop->last), @endif @endforeach
		@endif
		@if(count($bccRecipients) > 0)
			<br>
			bcc: @foreach($bccRecipients as $inboundRecipient){{ $recipient->email() }}@if(!$loop->last), @endif @endforeach
		@endif
		<br>
		<br>
		<strong>{{ $replyBody }}</strong>
		<br>
		<br>
		We have already been notified and will hopefully have a fix within the next hour. Please try again later or get in touch for an update on the fix. Again, we're really sorry about the inconvenience.
		<br>
		<br>
		Customer Support,
		<br>
		bemail
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent