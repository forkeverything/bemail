<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="/images/logo/favicon.png">

    <title>bemail</title>

    <link rel="stylesheet" href="/css/landing.css">
</head>
<body>


<div class="jumbotron jumbotron-fluid bg-primary text-white">
    <div class="container">
        <img src="/images/logo/svg/logo_white.svg" alt="Bemail logo" width="100px" class="mb-3">
        <h1 class="display-5">The e-mail translation service you've been looking for.</h1>
        <p class="lead">You write English. Your recipient
            writes Japanese. And we keep everybody on the same page.</p>
    </div>
</div>

<section id="hero">
    <div class="container">
        <div id="hero-content" class="row">
            <div id="hero-text" class="col-md-5">
                <div>
                    <img src="/images/logo/svg/logo_white.svg" alt="Bemail logo" class="hero_logo">
                    <h1>
                        We Translate Your Emails, Awesomely.
                    </h1>
                    <h3></h3>
                    <a href="#sign-up">
                        <button id="btn-hero-sign-up" type="button" class="btn btn-success btn-lg">I want perfect
                            English
                            now
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div id="hero-image">
                    <img src="/images/landing/hero-image.png" alt="Hero Image">
                </div>
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
</section>
<section id="email-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="heading">
                    Your Own Custom Email
                </h3>
                <p>
                    To your recipient, your email will just be like any other email. Professional and clear.
                </p>
            </div>
            <div class="col-sm-6">
                <h1 id="example-email">
                    <em>taro</em>@en.bemail.io
                </h1>
            </div>
        </div>
    </div>
</section>

<section id="professional" class="highlight">
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-sm-8 hidden-xs text">
                    @include('landing/professional')
                </div>
                <div class="col-sm-4 image"><img src="/images/landing/professional.png" alt="Professional"></div>
                <div class="col-sm-8 visible-xs text">
                    @include('landing/professional')
                </div>
            </div>
        </div>
    </div>
</section>
<section id="consistent" class="highlight">
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-sm-4 image"><img src="/images/landing/consistent.png" alt="Consistent"></div>
                <div class="col-sm-8 text">
                    <h3>Consistent</h3>
                    <p>
                        Using independent or different freelance translators can lead to completely different
                        translations, even for the same text. Our service makes sure that your translations are always
                        accurate, every time.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="simple" class="highlight">
    <div class="container">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="row">
                <div class="col-sm-8 text hidden-xs">
                    @include('landing/simple')
                </div>
                <div class="col-sm-4 image"><img src="/images/landing/simple.png" alt="Simple"></div>
                <div class="col-sm-8 text visible-xs">
                    @include('landing/simple')
                </div>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works">
    <img src="/images/landing/blue-bg.svg" alt="Blue Background" class="background">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>
                    How It Works
                </h1>
                <hr>
                <div class="row">
                    <div class="col-lg-6 col-lg-offset-3">
                        <p class="description">
                            We designed our service with a single goal, make english communication as <strong>simple as
                                possible</strong>. It's like having a native English translating staff working for you
                            24/7, only much cheaper.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div id="how-steps" class="col-sm-10 col-sm-offset-1">
                <div class="row first">
                    <div class="step col-sm-4">
                        <img src="/images/landing/icons/laptop-1.svg" alt="Sign Up">
                        <br>
                        <h4>Step 1</h4>
                        <p>Create an account using your current email.</p>
                    </div>
                    <div class="step col-sm-4">
                        <img src="/images/landing/icons/worldwide.svg" alt="Sign Up">
                        <br>
                        <h4>Step 2</h4>
                        <p>Set your english email.</p>
                    </div>
                    <div class="step col-sm-4">
                        <img src="/images/landing/icons/sharing.svg" alt="Sign Up">
                        <br>
                        <h4>Step 3</h4>
                        <p>Tell your foreign contacts about your new email address.</p>
                    </div>
                </div>
                <div class="row second">
                    <div class="step col-sm-4">
                        <img src="/images/landing/icons/transfer.svg" alt="Sign Up">
                        <br>
                        <h4>Step 4</h4>
                        <p>Automatically get translated emails in Japanese.</p>
                    </div>
                    <div class="step col-sm-4">
                        <img src="/images/landing/icons/laptop-2.svg" alt="Sign Up">
                        <br>
                        <h4>Step 5</h4>
                        <p>Login to send emails in Japanese.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="sign-up">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <h3>
                    We are on track to launch Sept, 2017.
                    <br>
                    Reserve your <strong>FREE</strong> email address now and we will notify you on launch.
                </h3>
                <form action="">
                    <div class="form-group">
                        <label for="input-username">Username</label>
                        <div class="input-group">
                            <input id="input-username" type="text" name="username" placeholder="you"
                                   class="form-control">
                            <div class="input-group-addon">@en.intermaiil.jp</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="input-email">Current Email</label>
                        <input id="input-email" type="email" class="form-control" placeholder="you@example.com">
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Reserve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</body>
</html>