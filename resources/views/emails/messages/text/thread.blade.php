@foreach($messages as $message)
@if($loop->first)
============================================================

@else
------------------------------------------------------------

@endif
@include('emails.messages.text.thread.single')
@endforeach