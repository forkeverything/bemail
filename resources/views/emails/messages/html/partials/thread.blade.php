@foreach($messages as $message)
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="100%"
			    style="@if($loop->first) border-top: 1px solid #eeeeee; @else border-top: 1px dotted #c5c5c5; @endif margin: 0;">
				@include('emails.messages.html.partials.thread.single')
			</td>
		</tr>
	</table>
@endforeach