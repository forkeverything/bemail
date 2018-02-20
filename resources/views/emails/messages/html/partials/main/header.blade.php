@if(isset($hash))
	<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 13px; line-height: 22px; margin-bottom: 0; padding: 0; color: #aaaaaa">
		Message: {{  $hash }}
	</p>
@endif
@if($slot)
	<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 18px; line-height: 25px; margin-bottom: 0; @if(isset($hash))margin-top: 0;@endif padding: 0; color: #1b1d1e">
		<strong>{{ $slot }}</strong>
	</p>
@endif