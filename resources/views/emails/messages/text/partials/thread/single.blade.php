{{ $message->readable_created_at }}
{{ $message->senderName() }} ({{ $message->senderEmail() }})

@include('emails.messages.text.partials.thread.single.recipients')


@if($message->translated_body)
# Translated
{{ $message->translated_body }}
@endif

# Original
{{ $message->body }}

