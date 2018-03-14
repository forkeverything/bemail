@extends('layouts.app')

@section('content')

	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card mt-5 border-primary">
				<div class="card-header bg-primary text-white">
					Login
				</div>
				<div class="card-body p-5">
					<form method="POST" action="{{ route('login') }}">
						{{ csrf_field() }}
						<!-- Email -->
						@component('auth.login.field', [
							'shouldValidate' => true,
							'fieldName' => 'email',
							'title' => 'E-mail Address'
						])
							<input id="login-email"
							       type="email"
							       class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}"
							       name="email"
							       value="{{ old('email') }}" required
							       autofocus>
						@endcomponent
						<!-- Password -->
						@component('auth.login.field', [
							'shouldValidate' => true,
							'fieldName' => 'password',
							'title' => 'Password'
						])
							<input id="login-password"
							       type="password"
							       class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}"
							       name="password"
							       required
							>
						@endcomponent
						<!-- Remember -->
						<div class="form-group row">
							<div class="checkbox offset-md-3 col-md-9">
								<label>
									<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
									Remember
									Me
								</label>
							</div>
						</div>
						<!-- Submit -->
						<div class="form-group row">
							<div class="offset-md-3 col-md-9">
								<button type="submit" class="btn btn-primary">
									Login
								</button>
								<a class="btn btn-link" href="{{ route('password.request') }}">
									Forgot Your Password?
								</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
