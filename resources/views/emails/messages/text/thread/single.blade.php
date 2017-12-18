{{ $message->readable_created_at }}
@if($message->is_reply)
{{ $message->reply_sender_email }}
@else
{{ $message->user->name }} ({{ $message->user->email }})
@endif

@include('emails.messages.text.thread.single.recipients')


@if($message->translated_body)
# Translated
{{ $message->translated_body }}
@endif

# Original
{{ $message->body }}

