<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		table td {
			border-collapse: collapse;
		}
		body.directional_text_wrapper {
			direction: rtl;
			unicode-bidi: embed;
		}
	</style>
</head>
<body>
	<div style="padding: 10px; line-height: 18px; font-family: Avenir, Helvetica, sans-serif; font-size: 12px; color: #444444;">
		<!-- Reply Line -->
		<div style="color: #b5b5b5">
			--- Please type your reply above this line ---
		</div>
		<!-- Line Break -->
		<p></p>
		<!-- Content Wrap -->
		<div style="margin-top: 25px;">
			{{ $slot }}
			{{--<!-- First Message -->--}}
			{{--<table width="100%" cellpadding="0" cellspacing="0" border="0">--}}
				{{--<!-- Content -->--}}
			{{--</table>--}}
			{{--<!-- Line Break -->--}}
			{{--<p></p>--}}
			{{--<!-- Second Message -->--}}
			{{--<table width="100%" cellpadding="0" cellspacing="0" border="0">--}}
				{{--<!-- Content -->--}}
			{{--</table>--}}
		</div>
		<!-- Footer -->
		@include('emails.messages.html.layout.footer')
	</div>
</body>
</html>
