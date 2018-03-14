<nav class="navbar navbar-expand-lg navbar-light mb-3 border-bottom box-shadow">

	<div class="container">
		<!-- Logo -->
		<a href="{{ url('/') }}" class="navbar-brand">
			<img width="75px" src="images/logo/jpg/logo_blue.jpg" alt="bemail">
		</a>

		<!-- Nav Toggler-->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<!-- Right Side Of Navbar-->
			<div id="navbarNav" class="navbar-collapse ml-auto collapse">
				<div class="navbar-nav ml-auto">
					@guest
						<a class="nav-link" href="{{ route('login') }}">Login</a>
						<a class="nav-link" href="{{ route('register') }}">Register</a>
					@else
						<a class="nav-link" href="/compose">New Message</a>
						<a class="nav-link" href="#" data-toggle="tooltip" title="Free word credits"
						   data-placement="bottom">{{ Auth::user()->credits }} Words</a>
						<a class="nav-link subscription" href="/subscription" >
							<span class="badge {{ Auth::user()->plan()->name() }} badge-secondary">{{ Auth::user()->plan()->name() }}</span>
						</a>
						<!-- User Dropdown-->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ Auth::user()->name }}
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarUserDropdown">
								<a class="dropdown-item" href="/account">Settings</a>
								<div>
									<a class="dropdown-item"
									   href="{{ route('logout') }}"
									   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
									>
										Logout
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</div>
							</div>
						</li>
					@endguest
				</div>
			</div>

	</div>

</nav>
