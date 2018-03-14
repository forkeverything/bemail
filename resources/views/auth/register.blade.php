@extends('layouts.app')

@section('content')


	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card mt-5 border-primary">
				<div class="card-header bg-primary text-white">
					Register
				</div>
				<div class="card-body p-5">
					<form class="form-horizontal" method="POST" action="{{ route('register') }}">
						{{ csrf_field() }}
						<!-- Name -->
						@component('auth.register.field', [
							'shouldValidate' => true,
							'fieldName' => 'name',
							'title' => 'Name'
						])
							<input id="register-name"
							       type="text"
							       class="form-control {{ $errors->has('name') ? 'border-danger' : '' }}"
							       name="name"
							       value="{{ old('name') }}"
							       required
							       autofocus
							>
						@endcomponent
						<!-- Email -->
						@component('auth.register.field', [
							'shouldValidate' => true,
							'fieldName' => 'email',
							'title' => 'E-mail Address'
						])
							<input id="register-email"
							       type="email"
							       class="form-control {{ $errors->has('email') ? 'border-danger' : '' }}"
							       name="email"
							       value="{{ old('email') }}"
							       required
							>
						@endcomponent
						<!-- Password -->
						@component('auth.register.field', [
							'shouldValidate' => true,
							'fieldName' => 'password',
							'title' => 'Password'
						])
							<input id="register-password"
							       type="password"
							       class="form-control {{ $errors->has('password') ? 'border-danger' : '' }}"
							       name="password"
							       required
							>
						@endcomponent
						<!-- Password Confirm -->
						@component('auth.register.field', [
						'fieldName' => 'password_confirmation',
						'title' => 'Confirm Password'
						])
							<input id="password-confirm"
							       type="password"
							       class="form-control"
							       name="password_confirmation"
							       required
							>
						@endcomponent
						<!-- Default Language -->
						@component('auth.register.field', [
							'shouldValidate' => true,
							'fieldName' => 'lang_default',
							'title' => 'Language'
						])
								<language-picker name="lang_default"
								                 :languages="{{ $languages }}"
								                 old-input="{{ old('lang_default') }}"
								                 class-prop="{{ $errors->has('lang_default') ? 'border-danger' : '' }}"
								></language-picker>
						@endcomponent

						<div class="form-group row">
							<div class="offset-md-3 col-md-8">
								<button type="submit" class="btn btn-primary">
									Register
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
