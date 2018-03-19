@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header')
			Your reply was not sent.
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			We're sorry to say that the following reply to {{ $message->originalMessage->sender_name }} ({{$message->originalMessage->sender_email }}) was unable to be sent due to a system error.
			<br>
			<br>
			<strong>"{{ $message->body  }}"</strong>
			<br>
			<br>
			Our systems have already notified us and we will be working as fast as we can to fix the issue. Please try again later or get in touch and we can provide you with an update.
			<br>
			<br>
			Customer Support,
			<br>
			bemail
		@endcomponent
	@endcomponent
	@include('emails.messages.html.partials.thread')
@endcomponent