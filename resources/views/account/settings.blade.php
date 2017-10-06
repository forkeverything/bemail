@extends('layouts.app')

@section('content')
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('flash::message')

        <form action="account" method="POST">
        {{ csrf_field() }}

        <!-- Name -->
            <div class="form-group">
                <label for="">Name</label>
                <input name="name" type="text" value="{{ $user->name }}" class="form-control">
                @if($errors->first('name'))
                    <p class="text-danger">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                @if($errors->first('email'))
                    <p class="text-danger">{{ $errors->first('email') }}</p>
                @endif
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
                <language-picker name="lang_default" default="{{ $user->defaultLanguage->code }}"></language-picker>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
