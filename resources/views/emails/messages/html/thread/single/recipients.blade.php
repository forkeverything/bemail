<!-- Standard (to) Recipients -->
to: @foreach($message->recipients()->standard()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
<!-- cc Recipients -->
@if($message->recipients()->cc()->get()->count() > 0)
	<br>
	cc: @foreach($message->recipients()->cc()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
@endif
<!-- bcc Recipients -->
@if($message->recipients()->bcc()->get()->count() > 0)
	<br>
	bcc: @foreach($message->recipients()->bcc()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
@endif