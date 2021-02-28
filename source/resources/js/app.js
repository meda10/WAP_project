require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './components/App'
import router from './routes'
import titleMixin from './titles'

Vue.mixin(titleMixin)
Vue.use(VueRouter);

const app = new Vue({
    el: '#app',    
    components: { App },
    router: new VueRouter(router)
});
