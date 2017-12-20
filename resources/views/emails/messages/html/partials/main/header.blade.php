@if(isset($hash))
	<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 13px; line-height: 22px; margin-bottom: 0; margin-top: 0; padding: 0; color: #bbbbbb">
		Message: {{  $hash }}
	</p>
@endif
<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 18px; line-height: 25px; margin-bottom: 0; margin-top: 0; padding: 0; color: #1b1d1e">
	<strong>{{ $slot }}</strong>
</p>