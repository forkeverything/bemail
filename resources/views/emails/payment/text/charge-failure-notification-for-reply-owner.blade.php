@component('emails.layout.text.main')
	You received a reply but we couldn't charge your account.

	{{ $message->sender_name }} ({{ $message->sender_email }}) sent a reply to your message but we couldn't translate it automatically because we were unsuccessful in charging your account.

	"{{ $message->body }}"

	If you would like us to translate it for you, please update your payment details and manually translate the email using our 'Send to Self' option when writing a new message.

	@include('emails.layout.text.message-thread')
@endcomponent