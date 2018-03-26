@component('emails.layout.html.main')
	@component('emails.layout.html.main.header')
		Your message was not translated or sent.
	@endcomponent
	@component('emails.layout.html.main.body')
		We were unsuccessful in charging your account for the following message:
		<br>
		<br>
		<strong>"{{ $message->body }}"</strong>
		<br>
		<br>
		<strong>Your message will not be translated or
			sent.</strong> If you would still like us to translate it for you, please update your payment details and try again.
	@endcomponent
@endcomponent