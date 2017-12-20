<table width="100% " cellpadding="0 " cellspacing="0 " border="0 " style="table-layout:fixed">
	<tr>
		<td width="100%" style="padding: 0; margin:0;" valign="top">
			<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 13px; line-height: 22px; margin-bottom: 0; margin-top: 15px; padding: 0; color: #bbbbbb">
				{{ $message->readable_created_at }}
			</p>
			<p style="font-family: Avenir, Helvetica, sans-serif; font-size: 15px; line-height: 22px; margin-bottom: 0; margin-top: 0; padding: 0; color: #1b1d1e">
			@if($senderName = $message->senderName())
				<strong>{{ $senderName }}</strong> ({{ $message->senderEmail() }})
			@else
				<strong>{{ $senderName }}</strong> ({{ $message->senderEmail() }})
			@endif
			</p>
			<!-- Recipients -->
			<p style="color: #2b2e2f; font-family: Avenir, Helvetica, sans-serif; font-size: 14px; line-height: 18px; margin: 0;">
				@include('emails.messages.html.partials.thread.single.recipients')
			</p>
			<!-- Translated Body -->
			@if($message->translated_body)
				<p style="color: #1b1d1e; font-family: Avenir, Helvetica, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0;">
					{{ $message->translated_body }}
				</p>
			@endif
			<!-- Original Body -->
			<p style="color: #bbbbbb; font-family: Avenir, Helvetica, sans-serif; font-size: 14px; line-height: 22px; margin: 15px 0;">
				{{ $message->body }}
			</p>
		</td>
	</tr>
</table>
