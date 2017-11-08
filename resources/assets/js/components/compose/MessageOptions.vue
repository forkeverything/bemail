<template>
<div class="message-options">
    <label class="checkbox-inline" :class="{ 'disabled': disableAutoTranslate }"><input type="checkbox" name="auto_translate_reply" v-model="autoTranslateReply" :disabled="disableAutoTranslate"><span data-toggle="tooltip" title="All replies will be translated into your default language at a fee to you.">Auto-Translate Reply</span></label>
    <label class="checkbox-inline"><input type="checkbox" name="send_to_self" v-model="sendToSelf"><span data-toggle="tooltip" title="Translated message will be sent back to you only.">Send to Self</span></label>
</div>
</template>
<script>
export default {
    data: function(){
        return {
            disableAutoTranslate: false,
            autoTranslateReply: 1,
            sendToSelf: 0
        }
    },
    watch: {
        sendToSelf: function(val) {
                this.disableAutoTranslate = val;
                this.autoTranslateReply = !val;
                vueGlobalEventBus.$emit('send-to-self', val);
        }
    },
    props: ['old-auto-translate-reply', 'old-send-to-self'],
    methods: {
        
    },
    mounted(){
    }
}
</script>