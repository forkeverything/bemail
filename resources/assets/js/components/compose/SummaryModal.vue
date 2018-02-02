<template>
    <div class="modal" tabindex="-1" role="dialog" ref="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Translate and send message?</h4>
                </div>
                <div class="modal-body">
                    <p>Please review the costs below for your message and hit send to confirm.</p>

                    <table class="table table-responsive table-bordered table-condensed">
                        <tbody>
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
                        <tr>
                            <td>Unit Price</td>
                            <td>$0.03</td>
                        </tr>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>$6.75</strong></td>
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
                unitPrice: 0
            }
        },
        computed: {
            wordsCharged() {
                let chargeable = this.wordCount - this.wordCredits;
                return chargeable > 0 ? chargeable : 0;
            },
            totalCost() {
                return this.wordsCharged * this.unitPrice;
            }
        },
        props: [
            'word-count',
            'word-credits',
            'lang-src',
            'lang-tgt'
        ],
        methods: {
            sendForm() {
                this.$emit('send-form');
            },
            fetchUnitPrice() {

            }
        },
        mounted() {
            vueGlobalEventBus.$on('show-summary-modal', () => {
                $(this.$refs.modal).modal();
            });
        }
    }
</script>