<template>
    <div class="file-input custom-file">
        <div class="form-group">
            <input :id="id"
                   class="custom-file-input"
                   type="file"
                   :name="name"
                   :multiple="multiple" ref="input"
                   @change="updateList"
            >
            <label class="custom-file-label" :for="id">Choose file</label>
        </div>
        <ul class="files-list list-unstyled list-inline" v-show="hasFiles">
            <li v-for="(file, index) in files" class="list-inline-item">
                <button type="button" class="btn btn-sm btn-secondary" @click="$refs.input.click()">
                    {{ file.name }}
                </button>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                files: []
            }
        },
        computed: {
            hasFiles() {
                return this.files.length > 0;
            }
        },
        props: ['name', 'id', 'multiple'],
        methods: {
            updateList() {
                this.files = [];
                let input = this.$refs.input;
                for (let i = 0; i < input.files.length; i++) {
                    this.files.push(input.files.item(i));
                }
            }
        },
        mounted() {

        }
    }
</script>