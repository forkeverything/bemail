@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')

        <form action="account" method="POST">
        {{ csrf_field() }}

        <!-- Name -->
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label class="control-label">Name</label>
                <input name="name" type="text" value="{{ $user->name }}" class="form-control">
                @include('layouts.single-error', ['errorField' => 'name'])
            </div>

            <!-- Email -->
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label class="control-label">Email</label>
                <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                @include('layouts.single-error', ['errorField' => 'email'])
            </div>

            <!-- Password -->
            <div class="form-group {{ $errors->has('pwd_current') || $errors->has('pwd_new') ? 'has-error' : '' }}">
                <label class="control-label">Password</label>
                <change-password-field pwd-current-error="{{ $errors->first('pwd_current') }}"
                                       pwd-new-error="{{ $errors->first('pwd_new') }}"
                ></change-password-field>

            </div>

            <!-- Default Language -->
            <div class="form-group {{ $errors->has('lang_default') ? 'has-error' : '' }}">
                <label class="control-label">
                    Your Default Language
                </label>
                <language-picker name="lang_default" default="{{ $user->defaultLanguage->code }}" :languages="{{ $languages }}"></language-picker>
                @include('layouts.single-error', ['errorField' => 'lang_default'])
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
