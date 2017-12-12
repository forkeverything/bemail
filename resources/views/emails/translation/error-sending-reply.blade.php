@component('mail::message')
# Your Reply Was Not Sent

We're sorry to say that your recent reply to {{ $originalMessage->user->name }} ({{$originalMessage->user->email}}) was unable to be sent due to a system error on our part. We're really very sorry for the inconvenience. Our systems have notified us and we are currently working as fast as we can to fix the problem.

If you have any suggestions or comments for us you can let us know by replying directly to this email.

Thanks for understanding,
Your friends at bemail

## Message That Failed To Send

{{ $body }}
@endcomponent