<template>
    <div class="tag-input">
            <pre ref="sizer"
                 class="sizer">{{ value }}</pre>
        <input ref="input"
               :value="value"
               :style="{ width: inputWidth }"
               @keydown.enter.prevent=""
               @keyup.enter.prevent.stop="addTag()"
               @keydown.188.prevent.stop="addTag()"
               @keydown.delete="deleteInput"
               @keydown.left="leftInput"
               @keydown.right="rightInput"
               @blur="blur"
               @input="onInput"
               :disabled="isDisabled"
        >
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                inputWidth: '20px'
            }
        },
        props: ['value', 'remove-tag', 'input-position', 'focus-tag', 'is-disabled'],
        watch: {
            value() {
                this.$nextTick(() => {
                    // 20px runway for calculating width time, needed to kill the flicker.
                    this.inputWidth = Math.round($(this.$refs.sizer).width()) + 20 + 'px';
                });
            }
        },
        methods: {
            onInput(event) {
                this.$emit('input', event.target.value);
            },
            addTag(endAddingRecipient){
                this.$emit('add-tag', endAddingRecipient);
            },
            blur(){
                this.addTag(true);
            },
            deleteInput: function () {
                // If at the end or just backspacing
                if (!this.inputPosition || this.value) return;
                this.removeTag(this.inputPosition - 1);
            },
            leftInput: function () {
                if (!this.$refs.input.selectionEnd) {
                    this.focusTag(this.inputPosition - 1);
                }
            },
            rightInput: function () {
                if (this.value.length === this.$refs.input.selectionEnd) this.focusTag(this.inputPosition);
            }
        }
    }
</script>