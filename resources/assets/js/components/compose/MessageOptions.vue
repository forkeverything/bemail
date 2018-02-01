<template>
<div class="message-options">
    <label class="checkbox-inline">
        <input type="checkbox"
               name="auto_translate_reply"
               :checked="autoTranslateReply"
               @change="changeAutoTranslateReply"
        >
        <span data-toggle="tooltip" title="All replies will be translated into your default language at a fee to you.">Auto-Translate Reply</span>
    </label>
    <label class="checkbox-inline">
        <input type="checkbox"
               name="send_to_self"
               :checked="sendToSelf"
               @change="changeSendToSelf"
        >
        <span data-toggle="tooltip" title="Translated message will be sent back to you only.">Send to Self</span>
    </label>
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