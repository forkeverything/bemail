<template>
<div class="credit-card">
    <div class="no-card" v-if="!hasCard">
        <p v-show="!showCardForm">You haven't added a credit or debit card.</p>
        <button v-show="!showCardForm" type="button" class="btn btn-outline-primary mb-5" @click="toggleCardForm">Add Card</button>
        <credit-card-form v-show="showCardForm" @cancel="toggleCardForm"></credit-card-form>
    </div>
    <div class="existing-card" v-else>
        Current Card!
    </div>
</div>
</template>
<script>
export default {
    data: function(){
        return {
            showCardForm: true
        }
    },
    computed: {
        hasCard() {
            return !! this.cardBrand;
        }
    },
    props: ['card-brand', 'card-last-four'],
    methods: {
        toggleCardForm() {
            this.showCardForm = !this.showCardForm;
        }
    }
}
</script>