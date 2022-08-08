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
import * as VueGoogleMaps from 'vue2-google-maps'
import Auth from './Auth.js';
import './helper';

Vue.prototype.auth = Auth;
Vue.use(VueAxios, axios)
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(VueRouter);
Vue.use(VueMeta);
Vue.use(VueSweetalert2);
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyCJ2mUQVfcR6lgS65M-hSrAkkwLHtdvX-U',
      libraries: 'places', // This is required if you use the Autocomplete plugin
      // OR: libraries: 'places,drawing'
      // OR: libraries: 'places,drawing,visualization'
      // (as you require)
   
      //// If you want to set the version, you can do so:
      // v: '3.26',
    },
   
    //// If you intend to programmatically custom event listener code
    //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
    //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
    //// you might need to turn this on.
    // autobindAllEvents: false,
   
    //// If you want to manually install components, e.g.
    //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
    //// Vue.component('GmapMarker', GmapMarker)
    //// then disable the following:
    // installComponents: true,
  })

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