@extends('layouts.app')
@section('content')
    <div class="container">
        @include('flash::message')
        <compose-form token="{{ csrf_token() }}"
                      :errors="{{ $errors }}"
                      recipients="{{ old('recipients') }}"
                      subject="{{ old('subject') }}"
                      :languages="{{ $languages }}"
                      lang-src="{{ old('lang_src') }}"
                      lang-tgt="{{ old('lang_tgt') }}"
                      :user-lang="{{ $userLang }}"
                      body="{{ old('body') }}"
        ></compose-form>
    </div>
@endsection

