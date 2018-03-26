@foreach($threadMessages as $message)
@if($loop->first)
============================================================

@else
------------------------------------------------------------

@endif
@include('emails.layout.text.message-thread.single-message')
@endforeach