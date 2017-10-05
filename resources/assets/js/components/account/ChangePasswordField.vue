<template>
    <div class="change-password-field">
        <a href="#" v-show="! show" @click.prevent="toggleField">Change Password</a>
        <div class="fields" v-show="show">
            <div>
                <label>
                    <small>Current</small>
                </label>
                <input type="password" name="pwd_current" class="form-control">
            </div>
            <div>
                <label>
                    <small>New</small>
                </label>
                <input type="password" name="pwd_new" class="form-control">
            </div>
            <div>
                <label>
                    <small>Confirm New</small>
                </label>
                <input type="password" name="pwd_confirm" class="form-control">
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function () {
            return {
                show: false
            }
        },
        methods: {
            toggleField(){
                this.show = !this.show;
            }
        }
    }
</script>