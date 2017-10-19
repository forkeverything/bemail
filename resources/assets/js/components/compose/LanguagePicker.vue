<template>
    <select :name="name" v-model="selected" class="form-control">
        <option value="" selected disabled>Pick One</option>
        <option v-for="language in languages" :value="language.code">{{ language.name }}</option>
    </select>
</template>
<script>
    export default {
        data: function () {
            return {
                selected: '',
                languages: ''
            };
        },
        props: ['name', 'default', 'old-input'],
        methods: {},
        mounted() {
            // Fetch languages
            axios.post('languages', {})
                .then((response) => {
                    // set languages
                    this.languages = response.data;
                    // set user default
                    this.selected = this.default ? this.default : '';
                    // if we have an old input - ie. form validation error redirect
                    if (this.oldInput) this.selected = this.oldInput;
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    };
</script>