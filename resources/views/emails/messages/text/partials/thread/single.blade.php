{{ $message->readable_created_at }}
@if($senderName = $message->senderName())
{{ $senderName }} ({{ $message->senderEmail() }})
@else
{{ $message->senderEmail() }}
@endif

@include('emails.messages.text.partials.thread.single.recipients')


@if($message->translated_body)
# Translated
{{ $message->translated_body }}
@endif

# Original
{{ $message->body }}

