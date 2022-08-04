import Vue from 'vue';
import VueRouter from 'vue-router';
import VueMeta from 'vue-meta';
import App from './App.vue';
import routes from './routes';
import axios from 'axios';
import VueAxios from 'vue-axios'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueSweetalert2 from 'vue-sweetalert2';
import Auth from './Auth.js';

Vue.prototype.auth = Auth;
Vue.use(VueAxios, axios)
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueRouter);
Vue.use(VueMeta);
Vue.use(VueSweetalert2);

const router = new VueRouter({
    routes,
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth) ) {
        if (Auth.check()) {
            next();
            return;
        } else {
            router.push('/');
        }
    } else {
        next();
    }
});

Vue.prototype.$appEvents = new Vue();

new Vue({
    router,
    render: (h) => h(App),
}).$mount('#app');