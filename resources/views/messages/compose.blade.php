@extends('layouts.app')
@section('content')
    <div class="container">
        <form action="compose" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="message-form-recipient">Recipients</label>
                <input name="recipient" type="text" id="message-form-recipient" class="form-control">
            </div>
            <div class="form-group">
                <label for="message-form-subject">Subject</label>
                <input name="subject" type="text" id="message-form-subject" class="form-control">
            </div>
            <div class="form-inline">
                <div class="form-group">
                    <label>From</label>
                    <language-picker name="lang_src" default="{{ $userLang->code }}"></language-picker>
                </div>
                <div class="form-group">
                    <label>To</label>
                    <language-picker name="lang_tgt"></language-picker>
                </div>
            </div>
            <div class="form-group">
                <textarea name="body" class="form-control" id="message-form-body" cols="30" rows="10" style="resize: none;"></textarea>
            </div>
                <button type="submit" class="btn btn-primary btn-sm">Send</button>
        </form>
    </div>
@endsection

