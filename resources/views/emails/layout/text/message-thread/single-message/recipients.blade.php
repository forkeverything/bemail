@if($message->has_recipients)
	to: @foreach($message->recipients()->standard()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
	@if($message->recipients()->cc()->get()->count() > 0)
		cc: @foreach($message->recipients()->cc()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
	@endif
	@if($message->recipients()->bcc()->get()->count() > 0)
		bcc: @foreach($message->recipients()->bcc()->get() as $recipient){{ $recipient->email }}@if(!$loop->last), @endif @endforeach
	@endif
@endif