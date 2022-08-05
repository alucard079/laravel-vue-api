import Vue from 'vue';
import Auth from './Auth.js';

Vue.mixin({
    methods: {
        can: (permission) => {
            const has_permission = Auth.user.permissions.includes(permission);
            if(has_permission) {
                return true;
            }
            return false;
        },
    },
});
