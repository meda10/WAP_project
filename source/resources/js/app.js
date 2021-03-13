require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import VueCookies from 'vue-cookies'
import App from './components/App'
import router from './routes'
import titleMixin from './titles'
import { ModalPlugin, ButtonPlugin } from 'bootstrap-vue'
import VueSessionStorage from 'vue-sessionstorage'

Vue.use(VueRouter);
Vue.use(VueCookies);
Vue.use(ModalPlugin);
Vue.use(ButtonPlugin);
Vue.use(VueSessionStorage);
Vue.mixin(titleMixin);

const app = new Vue({
    el: '#app',
    components: { App },
    router: new VueRouter(router)
});
