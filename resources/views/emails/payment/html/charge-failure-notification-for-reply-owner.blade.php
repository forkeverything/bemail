@component('emails.layout.html.main')
	@component('emails.layout.html.main.header')
		You received a reply but we couldn't charge your account.
	@endcomponent
	@component('emails.layout.html.main.body')
		{{ $message->sender_name }} ({{ $message->sender_email }}) sent a reply to your message but we couldn't translate it automatically because we were unsuccessful in charging your account.
		<br>
		<br>
		<strong>"{{ $message->body }}"</strong>
		<br>
		<br>
		If you would like us to translate it for you, please update your payment details and manually translate the email using our 'Send to Self' option when writing a new message.
	@endcomponent
	@include('emails.layout.html.message-thread')
@endcomponent