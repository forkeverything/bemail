@extends('layouts.app')
@section('content')
    <div class="container">
        @include('flash::message')
        <compose-form token="{{ csrf_token() }}"
                      word-credits="{{ Auth::user()->word_credits }}"
                      :errors="{{ $errors }}"
                      recipients="{{ old('recipients') }}"
                      subject-old="{{ old('subject') }}"
        ></compose-form>
    </div>
@endsection

