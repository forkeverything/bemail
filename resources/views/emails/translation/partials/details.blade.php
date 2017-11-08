<h3>Details</h3>
<strong>Received: </strong>
<br>
{{ $message->created_at->format('Y/m/d H:i') }}
<br>
<strong>Recipients: </strong> @if($message->has_recipients) @foreach($message->recipients as $recipient)<a href="mailto:{{ $recipient->email }}">{{ $recipient->email }}</a>@if(! $loop->last), @endif @endforeach @else Send to Self @endif
<br>
<strong>Translate:</strong> {{ $message->sourceLanguage->name }} <strong>to</strong> {{ $message->targetLanguage->name }}
<br>
<strong>Word Count:</strong> {{ $message->word_count }}