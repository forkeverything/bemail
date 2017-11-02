<h2>System Translation Error on {{ $messageWithError->error->created_at->format('Y/m/d H:i') }}</h2>
<h4>Code</h4>
{{ $messageWithError->error->code }}
<br>
<h4>Description</h4>
{{ $messageWithError->error->description }}
<br>
<h4>Sender</h4>
{{ $messageWithError->user->email }}