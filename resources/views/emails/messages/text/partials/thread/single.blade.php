{{ $message->readable_created_at }}
{{ $message->sender_name }} ({{ $message->sender_email }})

@include('emails.messages.text.partials.thread.single.recipients')


@if($message->translated_body)
# Translated
{{ $message->translated_body }}
@endif

# Original
{{ $message->body }}

