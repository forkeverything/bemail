@extends('layouts.app')
@section('content')
    <div class="container">

        @include('flash::message')

        <form action="compose" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group {{ $errors->has('recipients') ? 'has-error' : '' }}">
                <label for="message-form-recipient" class="control-label">Recipients</label>
                <recipients-input old-input="{{ old('recipients') }}"></recipients-input>
                @include('layouts.single-error', ['errorField' => 'recipients'])
            </div>
            <div class="form-group">
                <message-options old-auto-translate-reply="{{ old('auto_translate_reply') }}" old-send-to-self="{{ old('send_to_self') }}"></message-options>
            </div>
            <div class="form-group">
                <label for="message-form-subject">Subject</label>
                <input name="subject" type="text" id="message-form-subject" class="form-control" value="{{ old('subject') }}">
            </div>
            <div class="form-group {{ $errors->has('lang_src') || $errors->has('lang_tgt') ? 'has-error' : '' }}">
                <ul class="list-inline">
                    <li>
                        <div class="form-inline">
                            <language-picker :languages="{{ $languages }}" name="lang_src" default="{{ $userLang->code }}" old-input="{{ old('lang_src') }}"></language-picker>
                        </div>
                    </li>
                    <li><label class="control-label">To</label></li>
                    <li>
                        <div class="form-inline">
                            <language-picker :languages="{{ $languages }}" name="lang_tgt" old-input="{{ old('lang_tgt') }}"></language-picker>
                        </div>
                    </li>
                </ul>
                @include('layouts.single-error', ['errorField' => 'lang_src'])
                @include('layouts.single-error', ['errorField' => 'lang_tgt'])
            </div>
            <div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
                <textarea name="body" class="form-control" id="message-form-body" cols="30" rows="10"
                          style="resize: none;">{{ old('body') }}</textarea>
                @include('layouts.single-error', ['errorField' => 'body'])
            </div>
            <div class="form-group">
                <label for="message-form-attachments">Attachments</label>
                <file-input id="message-form-attachments" name="attachments[]" :multiple="true"></file-input>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
@endsection

