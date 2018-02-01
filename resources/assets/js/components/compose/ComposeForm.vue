<template>
    <form action="compose" method="post" enctype="multipart/form-data">
        <!-- CSRF Field -->
        <input type="hidden" name="_token" :value="token">
        <!-- Recipients -->
        <div class="form-group"
             :class="{
                'has-error': recipientsError
            }"
        >
            <label for="message-form-recipient" class="control-label">Recipients</label>
            <recipients-input :recipients="recipients" :send-to-self="sendToSelf"></recipients-input>
            <field-error v-if="recipientsError" :error="recipientsError"></field-error>
        </div>
        <!-- Message Options -->
        <div class="form-group">
            <message-options :auto-translate-reply="autoTranslateReply"
                             :send-to-self="sendToSelf"
                             @updated-send-to-self="updateSendtoSelf"
                             @updated-auto-translate-reply="updateAutoTranslateReply"
            ></message-options>
        </div>
        <!-- Subject -->
        <div class="form-group">
            <label for="message-form-subject">Subject</label>
            <input name="subject" type="text" id="message-form-subject" class="form-control" :value="subject">
        </div>
        <!-- Language Picker -->
        <div class="form-group"
             :class="{
                'has-error': hasLangError
            }"
        >
            <ul class="list-inline">
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_src"
                                         :languages="languages"
                                         :default="userLang.code"
                                         :old-input="langSrc"
                        ></language-picker>
                    </div>
                </li>
                <li><label class="control-label">To</label></li>
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_tgt"
                                         :languages="languages"
                                         :old-input="langTgt"
                        ></language-picker>
                    </div>
                </li>
            </ul>
            <field-error v-if="langSrcError" :error="langSrcError"></field-error>
            <field-error v-if="langTgtError" :error="langTgtError"></field-error>
        </div>
        <!-- Body -->
        <div class="form-group"
             :class="{
                'has-error': bodyError
             }"
        >
        <textarea name="body" class="form-control" id="message-form-body" cols="30" rows="10"
                  style="resize: none;">{{ body }}</textarea>
            <field-error v-if="bodyError" :error="bodyError"></field-error>
        </div>
        <!-- Attachments -->
        <div class="form-group">
            <label for="message-form-attachments">Attachments</label>
            <file-input id="message-form-attachments" name="attachments[]" :multiple="true"></file-input>
        </div>
        <!-- Submit -->
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</template>
<script>
    export default {
        data: function () {
            return {
                autoTranslateReply: true,
                sendToSelf: false
            }
        },
        computed: {
            recipientsError() {
                return this.errors.recipients ? this.errors.recipients[0] : '';
            },
            langSrcError() {
                return this.errors.lang_src ? this.errors.lang_src[0] : '';
            },
            langTgtError() {
                return this.errors.lang_tgt ? this.errors.lang_tgt[0] : '';
            },
            hasLangError() {
                return this.langSrcError || this.langTgtError;
            },
            bodyError() {
                return this.errors.body ? this.errors.body[0] : '';
            }
        },
        props: [
            'token',
            'errors',
            'recipients',
            'subject',
            'languages',
            'lang-src',
            'lang-tgt',
            'user-lang',
            'body'
        ],
        methods: {
            updateSendtoSelf(val) {
                this.sendToSelf = val;
            },
            updateAutoTranslateReply(val) {
                this.autoTranslateReply = val;
            }
        },
        mounted() {

        }
    }
</script>