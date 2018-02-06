<template>
    <form action="compose" method="post" enctype="multipart/form-data" ref="form">
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
            <input name="subject" type="text" id="message-form-subject" class="form-control" v-model="subject">
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
                                         :value="langSrc"
                                         :languages="languages"
                                         @picked-language="updateLang"
                        ></language-picker>
                    </div>
                </li>
                <li><label class="control-label">To</label></li>
                <li>
                    <div class="form-inline">
                        <language-picker name="lang_tgt"
                                         :value="langTgt"
                                         :languages="languages"
                                         @picked-language="updateLang"
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
            <p>Words: {{ wordCount }}</p>
            <textarea name="body"
                      class="form-control"
                      id="message-form-body"
                      cols="30"
                      rows="10"
                      v-model="body"
                      style="resize: none;"
            ></textarea>
            <field-error v-if="bodyError" :error="bodyError"></field-error>
        </div>
        <!-- Attachments -->
        <div class="form-group">
            <label for="message-form-attachments">Attachments</label>
            <file-input id="message-form-attachments" name="attachments[]" :multiple="true"></file-input>
        </div>
        <!-- Submit -->
        <button type="button" class="btn btn-primary" @click="showSummaryModal">Send</button>
        <summary-modal :word-count="wordCount"
                       @send-form="submit"
                       :word-credits="wordCredits"
                       :lang-src="langSrc"
                       :lang-tgt="langTgt"
                       :auto-translate-reply="autoTranslateReply"
        ></summary-modal>
    </form>
</template>
<script>
    export default {
        data: function () {
            return {
                autoTranslateReply: true,
                sendToSelf: false,
                langSrc: '',
                langTgt: '',
                subject: '',
                body: ''
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
            },
            wordCount() {
                if (!this.body) return 0;
                // Match any unicode Chinese / Japanese character or a space
                let matches = this.body.match(/[\u00ff-\uffff]|\S+/g);
                return matches ? matches.length : 0;
            }
        },
        props: [
            'token',
            'word-credits',
            'errors',
            'recipients',
            'subject-old',
            'languages',
            'user-lang',
            'lang-src-old',
            'lang-tgt-old',
            'body-old'
        ],
        methods: {
            updateSendtoSelf(val) {
                this.sendToSelf = val;
            },
            updateAutoTranslateReply(val) {
                this.autoTranslateReply = val;
            },
            showSummaryModal() {
                vueGlobalEventBus.$emit('show-summary-modal');
            },
            submit() {
                this.$refs.form.submit();
            },
            updateLang(name, val) {
                if (name == 'lang_tgt') this.langTgt = val;
                if (name == 'lang_src') this.langSrc = val;
            }
        },
        mounted() {
            this.langSrc = this.langSrcOld || this.userLang.code;
            this.langTgt = this.langTgtOld || '';
            this.subject = this.subjectOld || '';
            this.body = this.bodyOld || '';
        }
    }
</script>