require('./bootstrap');

import Vue from 'vue';
import VueI18n from 'vue-i18n';
import VueRouter from 'vue-router';
import VueCookies from 'vue-cookies';
import App from './components/App';
import titleMixin from './titles';
import { BootstrapVue, ModalPlugin, ButtonPlugin } from 'bootstrap-vue';
import VueSessionStorage from 'vue-sessionstorage';
import VueFormulate from '@braid/vue-formulate';
import { cs } from '@braid/vue-formulate-i18n';
import FormulateVSelectPlugin from '@cone2875/vue-formulate-select';
import FormulateAutocomplete from './components/FormulateAutocomplete';
import 'vue-select/dist/vue-select.css';
import axios from 'axios'
import router from './routes';
import checkPermissions from './permissions'

const axiosInstance = axios.create({
    baseURL: 'http://localhost:8080/api'
})

Vue.use(VueI18n);
Vue.component('FormulateAutocomplete', FormulateAutocomplete)

Vue.use(VueFormulate, {
    library: {
        autocomplete: {
            classification: 'text',
            component: 'FormulateAutocomplete'
        }
    },
    plugins: [ cs, FormulateVSelectPlugin ],

    locate: 'cs',
    classes: {
        inputHasErrors: 'is-invalid',
    },
    uploader: axiosInstance,
    uploadUrl: '/upload_image' ///public/img/movies
})
window.Vue = require('vue').default;

Vue.use(VueRouter);
Vue.use(VueCookies);
Vue.use(BootstrapVue);
Vue.use(ModalPlugin);
Vue.use(ButtonPlugin);
Vue.use(VueSessionStorage);
Vue.mixin(titleMixin);
Vue.use(checkPermissions);

const i18n = new VueI18n({
    locale: 'cs'
});

const app = new Vue({
    el: '#app',
    i18n,
    components: { App },
    router,
});
