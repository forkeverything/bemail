<template>
    <div class="change-password-field">
        <a href="#" v-show="! show" @click.prevent="toggleField">Change Password</a>
        <div class="fields" v-show="show">
            <div class="form-group">
                <label class="control-label">
                    <small>Current</small>
                </label>
                <input type="password" name="pwd_current" class="form-control">
                <p class="text-danger" v-if="pwdCurrentError"><small>{{ pwdCurrentError }}</small></p>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <small>New</small>
                </label>
                <input type="password" name="pwd_new" class="form-control">
                <p class="text-danger" v-if="pwdNewError"><small>{{ pwdNewError }}</small></p>
            </div>
            <div class="form-group">
                <label class="control-label">
                    <small>Confirm New</small>
                </label>
                <input type="password" name="pwd_new_confirmation" class="form-control">
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
        props: ['pwd-current-error', 'pwd-new-error'],
        methods: {
            toggleField(){
                this.show = !this.show;
            }
        },
        mounted() {
            if(this.pwdCurrentError || this.pwdNewError) {
                this.show = true;
            }

        }
    }
</script>