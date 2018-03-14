<template>
<div class="message-options form-check form-check-inline">

    <div class="form-check form-check-inline">

    <input type="checkbox"
           class="form-check-input"
           name="auto_translate_reply"
           :checked="autoTranslateReply"
           @change="changeAutoTranslateReply"
    >

    <label  class="form-check-label" data-toggle="tooltip" title="All replies will be translated into your default language at a fee to you.">Auto-Translate Reply</label>

    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input"
               type="checkbox"
               name="send_to_self"
               :checked="sendToSelf"
               @change="changeSendToSelf"
        >
        <label class="form-check-label" data-toggle="tooltip" title="Translated message will be sent back to you only.">Send to Self</label>
    </div>

</div>
</template>
<script>
export default {
    data: function(){
        return {

        }
    },
    watch: {

    },
    props: ['autoTranslateReply', 'sendToSelf'],
    methods: {
        changeAutoTranslateReply() {
            this.$emit('updated-auto-translate-reply', !this.autoTranslateReply);
            this.$emit('updated-send-to-self', false);
        },
        changeSendToSelf() {
            this.$emit('updated-send-to-self', !this.sendToSelf);
            this.$emit('updated-auto-translate-reply', false);
        }
    },
    mounted(){
    }
}
</script>