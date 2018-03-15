<template>
    <div class="modal" tabindex="-1" role="dialog" ref="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Translate and send message?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p>Please review the costs below for your message and hit send to confirm.</p>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>Language</td>
                            <!-- TODO ::: Localize language -->
                            <td><span v-if="langSrc && langTgt">{{ langDescription(langSrc) }} to {{ langDescription(langTgt) }}</span><span v-else>-</span></td>
                        </tr>
                        <tr>
                            <td>Word Count</td>
                            <td>{{ wordCount }}</td>
                        </tr>
                        <tr>
                            <td>Credits Available</td>
                            <td>{{ wordCredits }}</td>
                        </tr>
                        <tr>
                            <td>Words Charged</td>
                            <td>{{ wordsCharged }}</td>
                        </tr>
                        <tr :class="{ 'bg-light': !receivedUnitPrice }">
                            <td>Unit Price</td>
                            <td><span v-if="receivedUnitPrice">$ {{ unitPrice }}</span><span v-else>-</span></td>
                        </tr>
                        <tr :class="{ 'bg-light': !receivedUnitPrice }">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ totalCost }}</strong></td>
                        </tr>
                        </tbody>
                    </table>
                    <p><strong>'Auto-Translate Reply' is on.</strong> Your account will be charged for any translated
                        replies for from any of your recipients.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" @click="sendForm">Send</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</template>
<script>
    export default {
        data: function () {
            return {
                unitPrice: 0,
            }
        },
        computed: {
            receivedUnitPrice() {
                return this.unitPrice !== 0;
            },
            wordsCharged() {
                let chargeable = this.wordCount - this.wordCredits;
                return chargeable > 0 ? chargeable : 0;
            },
            totalCost() {
                if(isNaN(this.unitPrice)) return '-';
                return "$ " + (this.wordsCharged * this.unitPrice).toFixed(2);
            }
        },
        props: [
            'word-count',
            'word-credits',
            'lang-src',
            'lang-tgt',
            'auto-translate-reply'
        ],
        watch: {
            langSrc(val) {
                if (val && this.langTgt) this.fetchUnitPrice();
            },
            langTgt(val) {
                if (val && this.langSrc) this.fetchUnitPrice();
            }
        },
        methods: {
            sendForm() {
                this.$emit('send-form');
            },
            fetchUnitPrice() {
                this.unitPrice = 0;
                axios.get(`/languages/price/src/${this.langSrc}/tgt/${this.langTgt}`).then(res => {
                    this.unitPrice = res.data / 100;
                }).catch(err => {
                    this.unitPrice = 0;
                });
            },
            langDescription(code) {
                switch(code) {
                    case 'en':
                        return 'English';
                    case 'ja':
                        return 'Japanese';
                    case 'zh':
                        return 'Chinese';
                    default:
                        return '-'
                }
            }
        },
        mounted() {
            vueGlobalEventBus.$on('show-summary-modal', () => {
                $(this.$refs.modal).modal();
            });
        }
    }
</script>