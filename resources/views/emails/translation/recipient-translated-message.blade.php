Hi,
<br>
<br>
You have received a translated message from {{ $translatedMessage->user->name }} ({{ $translatedMessage->user->email }}).
<br>
<h3>Translated Message</h3>
{{ $translatedMessage->translated_body }}
<br>
@if($translatedMessage->auto_translate_reply)
    <h3>Replying</h3>
    {{ $translatedMessage->user->name }} has turned on 'Auto-Translate' for replies. This means that you can:
    <strong>a) Reply to This Email</strong> - Your message will automatically translated into {{ $translatedMessage->sourceLanguage->name }} at no cost to you.
    <br>
    <strong>b) Send a New Email to <a href="mailto:{{ $translatedMessage->user->email }}">{{ $translatedMessage->user->name }}</a></strong> - Your email will <em>not</em> be translated.
    <br>
@endif
<h3>Original Message</h3>
{{ $translatedMessage->body }}