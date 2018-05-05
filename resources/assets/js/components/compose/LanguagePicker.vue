<template>
    <select :name="name" v-model="selected" class="form-control" :class="classProp" @change="broadcast">
        <option value="" selected disabled>Pick One</option>
        <option v-for="language in languages" :value="language.code">{{ language.name }}</option>
    </select>
</template>
<script>
    export default {
        data: function () {
            return {
                selected: ''
            };
        },
        props: [
            'value',
            'class-prop',
            'languages',
            'name'
        ],
        watch: {
            value(val) {
                if(val) this.selected = val;
            }
        },
        methods: {
            broadcast(event) {
                // Notify parent components that a language has been picked.
                let lang = event.target.value;
                this.$emit('picked-language', this.name, lang);
            }
        },
        mounted() {}
    };
</script>