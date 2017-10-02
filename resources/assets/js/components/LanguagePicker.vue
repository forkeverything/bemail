<template>
    <select :name="name" v-model="selected">
        <option value="" selected disabled>Pick One</option>
        <option v-for="language in languages" :value="language.code">{{ language.name }}</option>
    </select>
</template>
<script>
    export default {
        data: function () {
            return {
                selected: '',
                languages: [
                    {
                        code: 'en',
                        name: 'English'
                    },
                    {
                        code: 'zh',
                        name: 'Chinese'
                    },
                    {
                        code: 'ja',
                        name: 'Japanese'
                    },
                    {
                        code: 'id',
                        name: 'Indonesian'
                    }
                ]
            };
        },
        props: ['name', 'default'],
        methods: {},
        mounted(){
            this.selected = this.default ? this.default : '';
        }
    };
</script>