<template>
    <div>
      <div class="d-flex align-items-center justify-content-center" v-if="!logged_user">
        <router-view></router-view>
      </div>
      <div v-else>
        <NavBar></NavBar>
        <b-container>
          <router-view></router-view>
        </b-container>
      </div>
    </div>
</template>

<script>
import NavBar from './includes/Navbar';
export default {
  components: {
    'NavBar': NavBar
  },
  data() {
      return {
        logged_user: this.auth.user,
        user: this.auth.user,
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
}
</script>