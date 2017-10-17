<template>
    <div class="file-input">
        <input :id="id" type="file" :name="name" :multiple="multiple" ref="input" @change="updateList">
        <ul class="files-list list-unstyled list-inline" v-show="multipleFiles">
            <li v-for="file in files">{{ file }}</li>
        </ul>
    </div>
</template>
<script>
export default {
    data: function(){
        return {
            files: []
        }
    },
    computed: {
      multipleFiles(){
          return this.files.length > 1;
      }
    },
    props:['name', 'id', 'multiple'],
    methods: {
        updateList(){
            this.files = [];
            let input = this.$refs.input;
            for(let i = 0; i < input.files.length; i++) {
                this.files.push(input.files.item(i).name);
            }
        }
    },
    mounted(){

    }
}
</script>