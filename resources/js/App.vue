<template>
    <div>
        <div
            v-if="!logged_user"
            class="d-flex align-items-center justify-content-center"
        >
            <router-view></router-view>
        </div>
        <div v-else>
            <nav-bar></nav-bar>
            <b-container>
                <router-view></router-view>
            </b-container>
        </div>
    </div>
</template>

<script>
import NavBar from "./includes/NavBar";
export default {
    components: {
        "nav-bar": NavBar,
    },
    data() {
        return {
            logged_user: this.auth.user,
            user: this.auth.user,
        };
    },
    created() {
        this.$appEvents.$on("logged-on", () => {
            this.logged_user = true;
        });
        this.$appEvents.$on("logged-out", () => {
            this.logged_user = false;
        });
    },
};
</script>
