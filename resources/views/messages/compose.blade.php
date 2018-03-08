@extends('layouts.app')
@section('content')
    <div class="container">
        @include('flash::message')
        <compose-form token="{{ csrf_token() }}"
                      word-credits="{{ Auth::user()->credits }}"
                      :errors="{{ json_encode($errors->getBag('default')) }}"
                      recipients="{{ old('recipients') }}"
                      subject-old="{{ old('subject') }}"
                      :languages="{{ $languages }}"
                      :user-lang="{{ $userLang }}"
                      lang-src-old="{{ old('lang_src') }}"
                      lang-tgt-old="{{ old('lang_tgt') }}"
                      body-old="{{ old('body') }}"
        ></compose-form>
    </div>
@endsection

