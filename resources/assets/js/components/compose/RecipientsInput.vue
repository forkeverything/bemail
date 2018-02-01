<template>
    <div class="recipients-input"
         @click="focusInput"
         ref="container"
         :class="{
            'disabled': sendToSelf
         }"
    >
        <div class="error" v-show="showError">
            {{ validateError }}
        </div>
        <div class="input form-control" :class="{ 'disabled': sendToSelf }">
            <input type="hidden" name="recipients" :value="value">
            <div class="input-wrap" v-if="inputPosition === 0">
                <tag-input v-model="newTag"
                           @add-tag="addTag"
                           :remove-tag="removeTag"
                           :focus-tag="focusTag"
                           :input-position="inputPosition"
                           :is-disabled="sendToSelf"
                >
                </tag-input>
            </div>
            <template v-for="(tag, index) in tags"
                      v-if="! emptyContainer"
            >
                <button type="button"
                        class="single-tag btn btn-tag"
                        @click.stop=""
                        @keydown.left.prevent.stop="leftTag(index)"
                        @keydown.delete.prevent.stop="removeTag(index)"
                        @keydown.right.prevent.stop="rightTag(index)"
                        :key="index"
                        :disabled="sendToSelf"
                >
                    {{ tag }}
                </button>
                <div class="input-wrap" v-if="inputPosition === (index + 1)">
                    <tag-input v-model="newTag"
                               @add-tag="addTag"
                               :remove-tag="removeTag"
                               :focus-tag="focusTag"
                               :input-position="inputPosition"
                               :is-disabled="sendToSelf"
                    >
                    </tag-input>
                </div>
            </template>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                tags: [],
                newTag: '',
                inputPosition: 0,
                showError: false,
                validateError: '',
            }
        },
        computed: {
            emptyContainer: function () {
                return this.tags.length < 1;
            },
            value: function () {
                return this.tags.join(",");
            }
        },
        props: ['recipients', 'send-to-self'],
        methods: {
            focusInput() {
                if(this.sendToSelf) return;
                $(this.$refs.container).find('.tag-input input').focus();
            },
            addTag: function (endAddingRecipient) {

                if(! this.newTag) return;

                if(! isValidEmail(this.newTag)) {
                    this.displayError("Please enter a valid email.");
                    return
                }


                // No empty tag with spaces
                if (!this.newTag.trim()) return;
                // insert it to where the input is
                this.tags.splice(this.inputPosition, 0, this.newTag);

                this.$emit('input', this.tags);

                // clear input
                this.newTag = '';
                // move input up 1
                this.inputPosition++;
                // re-focus
                if (!endAddingRecipient) this.$nextTick(this.focusInput);
            },
            removeTag: function (index) {
                // delete tag at index
                this.tags.splice(index, 1);
                this.$emit('input', this.tags);
                // move input position up to where removed tag was
                this.inputPosition = index;
                // re-focus
                this.$nextTick(this.focusInput);
            },
            focusTag: function (index) {
                let el = $(this.$refs.container).find('.single-tag')[index];
                if (el) {
                    $(el).focus();
                } else if (index === this.tags.length) {
                    // at end of tags - focus on input
                    this.inputPosition = index;
                    // at end of line, so focus on input
                    this.$nextTick(this.focusInput);
                }
            },
            leftTag: function (index) {
                if (this.inputPosition === index) {
                    this.focusInput();
                } else {
                    this.focusTag(index - 1);
                }
            },
            rightTag: function (index) {
                if (this.inputPosition === index + 1) {
                    this.focusInput();
                } else {
                    this.focusTag(index + 1);
                }
            },
            displayError(error) {
                this.validateError = error;
                this.showError = true;
                setTimeout(() => this.showError = false, 2500);
                return false;
            }
        },
        mounted(){
            this.inputPosition = this.tags.length;
            // Set tags if we have old recipients (ie. after validation error)
            if(this.recipients) {
                this.tags = this.recipients.split(',');
            }
        }
    }
</script>