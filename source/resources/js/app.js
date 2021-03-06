require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './components/App'
import router from './routes'
import titleMixin from './titles'
import axios from 'axios'

window.axios = axios
axios.defaults.baseURL = 'http://localhost:8000/'


Vue.use(VueRouter);
Vue.mixin(titleMixin);

const app = new Vue({
    el: '#app',
    components: { App },
    router: new VueRouter(router)
});
