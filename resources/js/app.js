require('./bootstrap');

window.Vue = require('vue');

import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';

window.Vue.use(ElementUI);

import Swal from 'sweetalert2';
window.Swal = Swal;

export const EventBus = new Vue();
window.EventBus = EventBus;


Vue.component('App', require('./components/App.vue').default);
Vue.component('Auth', require('./components/Auth.vue').default);

import router from './routes'

const app = new Vue({
    el: '#app',
    router
});
