@foreach($messages as $message)
@if($loop->first)
============================================================

@else
------------------------------------------------------------

@endif
@include('emails.messages.text.partials.thread.single')
@endforeach