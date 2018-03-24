@component('emails.messages.html.partials.layout')
	@component('emails.messages.html.partials.main')
		@component('emails.messages.html.partials.main.header')
			Reply not sent due to missing message.
		@endcomponent
		@component('emails.messages.html.partials.main.body')
			We recently received a reply email from you. Unfortunately, we couldn't track down the original message, and your reply was not sent. This could be due to the sender deleting the original message or an incorrect email address.
			<br>
			<br>
			<strong>"{{ $replyBody  }}"</strong>
			<br>
			<br>
			Please get in touch with our team if you would like any help,
			<br>
			Your friends at bemail.
		@endcomponent
	@endcomponent
@endcomponent