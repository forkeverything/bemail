<h2>System Translation Error on {{ $message->error->created_at->format('Y/m/d H:i') }}</h2>
<br>
<h4>Message</h4>
{{ $message->hash }}
<br>
<h4>Code</h4>
{{ $message->error->code }}
<br>
<h4>Description</h4>
{{ $message->error->msg }}
<br>
<h4>Sender</h4>
{{ $message->sender_email }}