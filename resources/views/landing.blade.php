<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<link rel="icon" type="image/png" href="/images/logo/favicon.png">

	<title>bemail</title>

	<link rel="stylesheet" href="/css/landing.css">
</head>
<body>

<!-- Hero -->
<div id="landing-hero" class="jumbotron jumbotron-fluid text-white mb-5">
	<div class="container text-center">
		<div class="row align-items-lg-center">
			<div class="col-12 col-lg-6">
				<img src="/images/logo/svg/logo_white.svg" alt="Bemail logo" width="100px" class="mb-3">
				<h1 class="display-5">The e-mail translation service you've been looking for.</h1>
				<p class="lead">You write English. Your recipient
					writes Japanese. And we keep everybody on the same page.</p>
			</div>
			<div class="col-12 col-lg-6">
				<img class="rocket-mail" src="/images/landing/hero-image.png" alt="Hero Image" width="100%">
			</div>
		</div>
		<div id="animated-mails">
			<img src="/images/landing/mails/mail-1.png" alt="Mail 1" class="mail1">
			<img src="/images/landing/mails/mail-2.png" alt="Mail 2" class="mail2">
			<img src="/images/landing/mails/mail-3.png" alt="Mail 3" class="mail3">
			<img src="/images/landing/mails/mail-4.png" alt="Mail 4" class="mail4">
			<img src="/images/landing/mails/mail-5.png" alt="Mail 5" class="mail5">
		</div>
	</div>
</div>

<!-- Main Feature - Professional -->
<div id="feature-professional" class="py-5">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-6 mx-auto mb-3 order-sm-2 mb-sm-0 col-sm-4 col-md-3">
				<img src="/images/landing/professional.png" alt="Professional" class="rounded-circle" width="100%">
			</div>
			<div class="col-12 text-center order-sm-1 col-sm-8 text-sm-left col-md-9">
				@include('landing/professional')
			</div>
		</div>
	</div>
</div>

<!-- Main Feature - Consistent -->
<div id="feature-consistent" class="py-5">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-6 mx-auto mb-3 mb-sm-0 col-sm-4 col-md-3">
				<img src="/images/landing/consistent.png" alt="Consistent" class="rounded-circle" width="100%">
			</div>
			<div class="col-12 text-center col-sm-8 text-sm-left col-md-9">
				<h3>Consistent</h3>
				Using independent or different freelance translators can lead to completely different
				translations, even for the same text. Our service makes sure that your translations are always
				accurate, every time.
			</div>
		</div>
	</div>
</div>

<!-- Main Feature - Simple -->
<div id="feature-simple" class="py-5 mb-5">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-6 mx-auto mb-3 order-sm-2 mb-sm-0 col-sm-4 col-md-3">
				<img src="/images/landing/simple.png" alt="Simple" class="rounded-circle" width="100%">
			</div>
			<div class="col-12 text-center order-sm-1 col-sm-8 text-sm-left col-md-9">
				@include('landing/simple')
			</div>
		</div>
	</div>
</div>
<!-- How It Works -->
<div id="how-it-works" class="text-white mb-5">
	<img class="background d-none d-sm-block"
	     src="/images/landing/blue-bg.svg" alt="Blue Background">
	<div class="container content text-center">
		<h2>How It Works</h2>
		<p class="mb-5">
			We designed our service with a single goal, make english communication as <strong>simple as
				possible</strong>. It's like having a native English translating staff working for you
			24/7, only much cheaper.
		</p>
		<div class="row">
			<div class="col-sm-4 mb-3">
				<div class="col-3 col-sm-8 col-md-6 col-lg-4 mx-auto">
					<img width="100%" src="/images/landing/icons/laptop-1.svg" alt="Sign Up">
				</div>
				<h4 class="badge badge-success">Step 1</h4>
				<p>Create an account using your current email.</p>
			</div>
			<div class="col-sm-4 mb-3">
				<div class="col-3 col-sm-8 col-md-6 col-lg-4 mx-auto">
					<img width="100%" src="/images/landing/icons/worldwide.svg" alt="Sign Up">
				</div>
				<h4 class="badge badge-success">Step 2</h4>
				<p>Set your english email.</p>
			</div>
			<div class="col-sm-4 mb-3">
				<div class="col-3 col-sm-8 col-md-6 col-lg-4 mx-auto">
					<img width="100%" src="/images/landing/icons/sharing.svg" alt="Sign Up">
				</div>
				<h4 class="badge badge-success">Step 3</h4>
				<p>Tell your foreign contacts about your new email address.</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4 mb-3 mb-sm-0">
				<div class="col-3 col-sm-8 col-md-6 col-lg-4 mx-auto">
					<img width="100%" src="/images/landing/icons/transfer.svg" alt="Sign Up">
				</div>
				<h4 class="badge badge-success">Step 4</h4>
				<p>Automatically get translated emails in Japanese.</p>
			</div>
			<div class="col-sm-4">
				<div class="col-3 col-sm-8 col-md-6 col-lg-4 mx-auto">
					<img width="100%" src="/images/landing/icons/laptop-2.svg" alt="Sign Up">
				</div>
				<h4 class="badge badge-success">Step 5</h4>
				<p>Login to send emails in Japanese.</p>
			</div>
		</div>
	</div>
</div>

<!-- Sign Up Form -->
<div id="sign-up" class="text-center py-5 mb-5">
	<div class="container py-5">
		<div class="row justify-content-center">
			<div class="col-12 col-md-11 col-lg-8">
				<h3>
					We are on track to launch May, 2018.
				</h3>
				<p>
					Register your interest now and receive 1 month of pro account for free when we launch.
				</p>
				<form>
					<div class="form-row">
						<div class="form-group col-9 col-md-10">
							<input type="email" class="form-control" id="sign-up-email" placeholder="Enter email">
						</div>
						<div class="form-group col-3 col-md-2">
							<button type="submit" class="btn btn-primary">Register</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

</body>
</html>