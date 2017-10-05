@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="">
        {{ csrf_field() }}

        <!-- Name -->
            <div class="form-group">
                <label for="">Name</label>
                <input name="name" type="text" value="{{ $user->name }}" class="form-control">
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" name="email" value="{{ $user->email }}" class="form-control">
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="">Password</label>
                <change-password-field></change-password-field>
            </div>

            <!-- Default Language -->
            <div class="form-group">
                <label>
                    Your Language
                </label>
                <language-picker name="lang_tgt"  default="{{ $user->defaultLanguage->code }}"></language-picker>
            </div>


            <!-- Submit -->
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
