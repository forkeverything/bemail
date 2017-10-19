@extends('layouts.app')
@section('content')
    <div class="container">

        @include('flash::message')

        <form action="compose" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="message-form-recipient">Recipients</label>
                @include('layouts.single-error', ['errorField' => 'recipients'])
                <recipients-input old-input="{{ old('recipients') }}"></recipients-input>
            </div>
            <div class="form-group">
                <label for="message-form-subject">Subject</label>
                <input name="subject" type="text" id="message-form-subject" class="form-control" value="{{ old('subject') }}">
            </div>
            @include('layouts.single-error', ['errorField' => 'lang_src'])
            @include('layouts.single-error', ['errorField' => 'lang_tgt'])
            <ul class="list-inline form-group">
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_src" default="{{ $userLang->code }}" old-input="{{ old('lang_src') }}"></language-picker>
                    </div>
                </li>
                <li><label>To</label></li>
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_tgt" old-input="{{ old('lang_tgt') }}"></language-picker>
                    </div>
                </li>
            </ul>
            <div class="form-group">
                @include('layouts.single-error', ['errorField' => 'body'])
                <textarea name="body" class="form-control" id="message-form-body" cols="30" rows="10"
                          style="resize: none;">{{ old('body') }}</textarea>
            </div>
            <div class="form-group">
                <label for="message-form-attachments">Attachments</label>
                <file-input id="message-form-attachments" name="attachments[]" :multiple="true"></file-input>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
@endsection

