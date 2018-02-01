
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
Vue.component('compose-form', require('./components/compose/ComposeForm.vue'));
Vue.component('language-picker', require('./components/compose/LanguagePicker.vue'));
Vue.component('recipients-input', require('./components/compose/RecipientsInput.vue'));
Vue.component('tag-input', require('./components/compose/TagInput.vue'));
Vue.component('file-input', require('./components/compose/FileInput.vue'));
Vue.component('message-options', require('./components/compose/MessageOptions.vue'));
// Account
Vue.component('change-password-field', require('./components/account/ChangePasswordField.vue'));
// System
Vue.component('field-error', require('./components/layout/FieldError.vue'));

// Event
window.vueGlobalEventBus = new Vue();

/**
 * Vue Instance
 * @type {Vue}
 */

const app = new Vue({
    el: '#app'
});