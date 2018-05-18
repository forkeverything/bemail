<template>
    <div class="add-credit-card">
        <form action="/payment/card" method="post" id="payment-form" @submit.prevent="getToken">

            <div class="form-group">
                <label for="add-card-name">Cardholder Name</label>
                <input type="text" id="add-card-name" class="form-control" v-model="name">
            </div>

            <div class="form-group">
                <label for="add-card-address-1">Address</label>
                <input type="text" id="add-card-address-1" class="form-control" v-model="addressLine1">
            </div>

            <div class="form-group">
                <input type="text" id="add-card-address-2" class="form-control" v-model="addressLine2">
            </div>

            <div class="form-row">
                <div class="form-group col-sm-4">
                    <label for="add-card-city">City</label>
                    <input type="text" id="add-card-city" class="form-control" v-model="addressCity">
                </div>
                <div class="form-group col-sm-4">
                    <label for="add-card-state">State</label>
                    <input type="text" id="add-card-state" class="form-control" v-model="addressState">
                </div>
                <div class="form-group col-sm-4">
                    <label for="add-card-zip">Postal Code</label>
                    <input type="text" id="add-card-zip" class="form-control" v-model="addressZip">
                </div>
            </div>

            <div class="form-group">
                <label for="add-card-country">Country</label>
                <select name="" id="add-card-country" class="custom-select" v-model="addressCountry">
                    <option value="" selected disabled>Select a country</option>
                    <option value="JP">Japan</option>
                    <option value="US">United States</option>
                </select>
            </div>
            
            <label for="card-element">
                 Credit or Debit Card
            </label>
            <div class="form-group">
                <div id="card-element" ref="card">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
            </div>
            <!-- Used to display Element errors. -->
            <div id="card-errors" role="alert"></div>
            <div class="mb-5">
                <button type="submit" class="btn btn-success mr-2">Save Card</button>
                <button type="button" @click="cancelAddingCard" class="btn btn-outline-secondary">Cancel</button>
            </div>
        </form>
    </div>
</template>
<script>
    import {Stripe, createStripe} from '../../stripe.js';

    export default {
        data: function () {
            return {
                card: '',
                name: '',
                addressLine1: '',
                addressLine2: '',
                addressCity: '',
                addressState: '',
                addressZip: '',
                addressCountry: ''
            }
        },
        methods: {
            getToken() {
                Stripe.instance.createToken(this.card, {
                    "name": this.name,
                    "address_line_1": this.addressLine1,
                    "address_line_2": this.addressLine2,
                    "address_city": this.addressCity,
                    "addressState": this.addressState,
                    "addressZip": this.addressZip,
                    "addressCountry": this.addressCountry
                }).then((result) => {
                    console.log(result);
                });
            },
            cancelAddingCard() {
                this.card.clear();
                this.$emit('cancel');
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
