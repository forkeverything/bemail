@extends('layouts.app')
@section('content')
    <div class="container">
        <form action="compose" method="post">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="message-form-recipient">Recipients</label>
                <recipients-input></recipients-input>
            </div>
            <div class="form-group">
                <label for="message-form-subject">Subject</label>
                <input name="subject" type="text" id="message-form-subject" class="form-control">
            </div>
            <ul class="list-inline form-group">
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_src" default="{{ $userLang->code }}"></language-picker>
                    </div>
                </li>
                <li><label>To</label></li>
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_tgt"></language-picker>
                    </div>
                </li>
            </ul>
            <div class="form-group">
                <textarea name="body" class="form-control" id="message-form-body" cols="30" rows="10"
                          style="resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
@endsection

