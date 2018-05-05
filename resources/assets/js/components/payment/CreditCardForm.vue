<template>
    <div class="add-credit-card">
        <form action="/payment/card" method="post" id="payment-form" @submit.prevent="getToken">
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
            <button type="submit" class="btn btn-primary">Save Card Payment</button>
        </form>
    </div>
</template>
<script>
    import {Stripe, createStripe} from '../../stripe.js';

    export default {
        data: function () {
            return {
                card: ''
            }
        },
        methods: {
            getToken() {
                console.log('submit');
                Stripe.instance.createToken(this.card).then((result) => {
                    console.log(result);
                });
            }
        },
        mounted() {
            // Create an instance of the card Element.
            this.card = createStripe('card');
            // Add an instance of the card Element into the `card-element` <div>.
            this.card.mount(this.$refs.card)


        }
    }
</script>