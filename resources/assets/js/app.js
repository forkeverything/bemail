
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Components
 */
// Compose
Vue.component('language-picker', require('./components/compose/LanguagePicker.vue'));
Vue.component('recipients-input', require('./components/compose/RecipientsInput.vue'));
Vue.component('tag-input', require('./components/compose/TagInput.vue'));
Vue.component('file-input', require('./components/compose/FileInput.vue'));
// Account
Vue.component('change-password-field', require('./components/account/ChangePasswordField.vue'));

/**
 * Vue Instance
 * @type {Vue}
 */

const app = new Vue({
    el: '#app'
});