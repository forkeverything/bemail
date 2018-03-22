<template>
    <form action="compose" method="post" enctype="multipart/form-data" ref="form">
        <!-- CSRF Field -->
        <input type="hidden" name="_token" :value="token">
        <!-- Recipients -->
        <div class="form-group">
            <label for="message-form-recipient" class="control-label">Recipients</label>
            <recipients-input :recipients="recipients"
                              :send-to-self="sendToSelf"
                              @update-recipients="updateRecipients"
            ></recipients-input>
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
        <div class="form-group">
            <label>Translate From</label>
            <div class="row align-items-center">
                <div class="col-5 col-md-4 col-lg-3 col-xl-2">
                    <language-picker name="lang_src"
                                     :value="langSrc"
                                     :languages="languages"
                                     @picked-language="updateLang"
                    ></language-picker>
                </div>
                    <label class="control-label mb-0">To</label>
                <div class="col-5 col-md-4 col-lg-3 col-xl-2">
                    <language-picker name="lang_tgt"
                                     :value="langTgt"
                                     :languages="languages"
                                     @picked-language="updateLang"
                    ></language-picker>
                </div>
            </div>
            <field-error v-if="langSrcError" :error="langSrcError"></field-error>
            <field-error v-if="langTgtError" :error="langTgtError"></field-error>
        </div>
        <!-- Body -->
        <div class="form-group"
        >
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
        <div class="form-group mb-5">
            <label for="message-form-attachments" class="d-flex justify-content-between align-items-center">Attachments <small>Max total size 35MB</small></label>
            <file-input id="message-form-attachments" name="attachments[]" :multiple="true"></file-input>
            <field-error v-if="attachmentsError" :error="attachmentsError"></field-error>
        </div>
        <!-- Submit -->
        <div class="form-group mb-5">
            <button type="button" class="btn btn-block btn-lg btn-primary" @click="showSummaryModal" :disabled="!canSend">Send</button>
        </div>
        <summary-modal @send-form="submit"
                       :body="body"
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
                recipients: '',
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
            attachmentsError() {
                return this.errors.attachments ? this.errors.attachments[0] : '';
            },
            hasRecipients() {
                return !!this.recipients;
            },
            hasSubject() {
                return !!this.subject;
            },
            hasValidLanguages() {
                return !!this.langSrc && !!this.langTgt && this.langSrc !== this.langTgt;
            },
            hasBody() {
                return !!this.body;
            },
            canSend() {
                return this.hasRecipients && this.hasSubject && this.hasValidLanguages && this.hasBody;
            }
        },
        props: [
            'token',
            'word-credits',
            'errors',
            'recipients-old',
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
                if (name === 'lang_tgt') this.langTgt = val;
                if (name === 'lang_src') this.langSrc = val;
            },
            updateRecipients(recipients) {
                this.recipients = recipients;
            }
        },
        mounted() {
            this.recipients = this.recipientsOld || '';
            this.langSrc = this.langSrcOld || this.userLang.code;
            this.langTgt = this.langTgtOld || '';
            this.subject = this.subjectOld || '';
            this.body = this.bodyOld || '';
        }
    }
</script>