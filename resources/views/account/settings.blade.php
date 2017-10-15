@extends('layouts.app')

@section('content')
    <div class="container">
        @include('flash::message')

        <form action="account" method="POST">
        {{ csrf_field() }}

        <!-- Name -->
            <div class="form-group">
                <label for="">Name</label>
                @include('layouts.single-error', ['errorField' => 'name'])
                <input name="name" type="text" value="{{ $user->name }}" class="form-control">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="">Email</label>
                @include('layouts.single-error', ['errorField' => 'email'])
                <input type="text" name="email" value="{{ $user->email }}" class="form-control">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="">Password</label>
                <change-password-field pwd-current-error="{{ $errors->first('pwd_current') }}"
                                       pwd-new-error="{{ $errors->first('pwd_new') }}"
                ></change-password-field>

            </div>

            <!-- Default Language -->
            <div class="form-group">
                <label>
                    Your Default Language
                </label>
                @include('layouts.single-error', ['errorField' => 'lang_default'])
                <language-picker name="lang_default" default="{{ $user->defaultLanguage->code }}"></language-picker>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
