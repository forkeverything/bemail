@component('emails.messages.text.partials.layout')

Your reply was not sent.

We're sorry to say that the following reply to {{ $message->originalMessage->sender_name }} ({{$message->originalMessage->sender_email }}) was unable to be sent due to a system error.

"{{ $message->body  }}"

Our systems have already notified us and we will be working as fast as we can to fix the issue. Please try again later or get in touch and we can provide you with an update.

Customer Support,
bemail

@include('emails.messages.text.partials.thread')
@endcomponent