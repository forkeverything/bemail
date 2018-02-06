@extends('layouts.app')
@section('content')
    <div class="container">
        @include('flash::message')
        <compose-form token="{{ csrf_token() }}"
                      word-credits="{{ Auth::user()->word_credits }}"
                      recipients="{{ old('recipients') }}"
        ></compose-form>
    </div>
@endsection

