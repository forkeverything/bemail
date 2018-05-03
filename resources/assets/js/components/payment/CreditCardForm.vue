<template>
    <div class="add-credit-card">
        <form action="/payment/card" method="post" id="payment-form">
                <label for="card-element">
                    Credit or debit card
                </label>
                <div class="form-group">
                    <div id="card-element" ref="card">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                </div>
                <!-- Used to display Element errors. -->
                <div id="card-errors" role="alert"></div>
            <button type="button" class="btn btn-primary">Save Card Payment</button>
        </form>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                apiKey: 'pk_test_AxvwvLmtzAdD3DJYKHeu7l4G'
            }
        },
        methods: {},
        mounted() {
            let key = $('meta[name="stripe-key"]').attr('content');
            let stripe = Stripe(key);
            let elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '16px',
                    color: "#32325d",
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount(this.$refs.card);


        }
    }
</script>