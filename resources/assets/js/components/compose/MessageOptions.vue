<template>
<div class="message-options">
    <label class="checkbox-inline" :class="{ 'disabled': disableAutoTranslate }"><input type="checkbox" name="auto_translate_reply" v-model="autoTranslateReply" :disabled="disableAutoTranslate">Auto-Translate Reply</label>
    <label class="checkbox-inline"><input type="checkbox" name="send_to_self" v-model="sendToSelf">Send to Self</label>
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
        this.autoTranslateReply = this.oldAutoTranslateReply ? 1 : 0;
        this.sendToSelf = this.oldSendToSelf ? 1 : 0;
    }
}
</script>