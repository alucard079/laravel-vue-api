<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="info">
            <b-navbar-brand to="/dashboard">Fligno</b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <b-nav-item to="/dashboard">Home</b-nav-item>
                    <b-nav-item v-if="can('permission.view')" to="/permissions"
                        >Permissions</b-nav-item
                    >
                    <b-nav-item v-if="can('role.view')" to="/roles"
                        >Roles</b-nav-item
                    >
                    <b-nav-item v-if="can('user.view')" to="/users"
                        >Users</b-nav-item
                    >
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item @click="logout">Logout</b-nav-item>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>
import Auth from "../Auth.js";
export default {
    methods: {
        logout() {
            this.axios
                .post("/api/logout")
                .then((response) => {
                    if (response.status === 200) {
                        Auth.logout(); //reset local storage
                        this.$appEvents.$emit("logged-out");
                        this.$router.push("/");
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
