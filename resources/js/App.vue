<template>
    <div>
      <div class="d-flex align-items-center justify-content-center" v-if="!logged_user">
        <router-view></router-view>
      </div>
      <div v-else>
        <b-navbar toggleable="lg" type="dark" variant="info">
          <b-navbar-brand to="/dashboard">Fligno</b-navbar-brand>

          <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

          <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav>
              <b-nav-item to="/dashboard">Home</b-nav-item>
              <b-nav-item to="/users">Users</b-nav-item>
              <b-nav-item to="/permissions">Permissions</b-nav-item>
            </b-navbar-nav>

            <!-- Right aligned nav items -->
            <b-navbar-nav class="ml-auto" v-if="logged_user">
              <b-nav-item @click="logout">Logout</b-nav-item>
            </b-navbar-nav>
          </b-collapse>
        </b-navbar>
        <b-container>
          <router-view></router-view>
        </b-container>
      </div>
    </div>
</template>

<script>
import Auth from './Auth.js';
export default {
  data() {
      return {
        logged_user: this.auth.user,
        user: '',
      };
  },
  created() {
     this.$appEvents.$on('logged-on', () => {
        this.logged_user = true;
     });
     this.$appEvents.$on('logged-out', () => {
        this.logged_user = false;
     });
  },
  methods: {
    logout() {
      this.axios.post('/api/logout')
        .then(response => {
            if(response.status === 200) {
              Auth.logout(); //reset local storage
              this.$appEvents.$emit('logged-out');
              this.$router.push('/');
            }
        })
        .catch((error) => {
            console.log(error);
        });
    }
  }
}
</script>