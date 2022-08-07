<template>
  <div>
    <div class="auth-container">
      <div class="auth-box">
        <p class="auth-header">Register</p>
        <b-form @submit.prevent="onSubmit" @reset.prevent="onReset">

          <b-form-group
            label="Name:"
          >
            <b-form-input
              v-model="form.name"
              :state="form.name_state"
              type="text"
              placeholder="Enter name"
            ></b-form-input>
            <b-form-invalid-feedback :state="form.name_state">
                {{form.name_error}}
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group
            label="Email address:"
          >
            <b-form-input
              v-model="form.email"
              :state="form.email_state"
              type="text"
              placeholder="Enter email"
            ></b-form-input>
            <b-form-invalid-feedback :state="form.email_state">
                {{form.email_error}}
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Password:">
            <b-form-input
              type="password"
              v-model="form.password"
              :state="form.password_state"
              placeholder="Enter password"
            ></b-form-input>

            <b-form-invalid-feedback :state="form.password_state">
                {{form.password_error}}
            </b-form-invalid-feedback>
          </b-form-group>

          <b-form-group label="Confirm Password:">
            <b-form-input
              type="password"
              v-model="form.password_confirmation"
              :state="form.password_confirmation_state && form.password_state"
              placeholder="Confirm password"
            ></b-form-input>

            <b-form-invalid-feedback :state="form.password_confirmation_state && form.password_state">
              {{form.password_confirmation_error ? form.password_confirmation_error : form.password_error ? form.password_error : ''}}
            </b-form-invalid-feedback>
          </b-form-group>

          <b-button type="submit" variant="primary" v-if="!loading">Submit</b-button>
          <b-button type="submit" variant="primary" v-else>
            <b-spinner small variant="primary" label="Spinning"></b-spinner>
            Loading...
          </b-button>
          <b-button type="reset" variant="danger">Reset</b-button>
          <b-button to="/" variant="info">Login</b-button>
        </b-form>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        loading: false,
        form: {
            name: null,
            name_state: null,
            name_error: null,

            email: null,
            email_state: null,
            email_error: null,

            password: null,
            password_state: null,
            password_error: null,

            password_confirmation: null,
            password_confirmation_state: null,
            password_confirmation_error: null,
        }
      }
    },
    methods: {
      onSetError(vmodel_error, value, state) {
        if(value) {
            this.form[vmodel_error] = value;
            this.form[state] = value ? false : true;
        } else {
            this.form[vmodel_error] = value;
            this.form[state] = value === null ? true : false;
        }
      },
      onSubmit() {
        this.loading = true;
        let form = {
            name: this.form.name,
            email: this.form.email,
            password: this.form.password,
            password_confirmation: this.form.password_confirmation,
        }

        this.axios.post('/api/register', form)
        .then(response => {
            this.loading = false;
            let data = response.data;
            if(response.status === 200) {
                this.$swal({
                    title: 'Success!',
                    text: data.message,
                    icon: 'success',
                }).then((res) => {
                    if(res.isConfirmed) {
                        this.$router.push('/');
                    }
                });
            }
        })
        .catch(error => {
            this.loading = false;
            let errors = error.response.data.errors;
            if(errors) {
              errors.name ?
                this.onSetError('name_error', errors.name[0], 'name_state'):
                this.onSetError('name_error', null, 'name_state');
              errors.email ?
                this.onSetError('email_error', errors.email[0], 'email_state'):
                this.onSetError('email_error', null, 'email_state');
              errors.password ?
                this.onSetError('password_error', errors.password[0], 'password_state'):
                this.onSetError('password_error', null, 'password_state');
              errors.password_confirmation ?
                this.onSetError('password_confirmation_error', errors.password_confirmation[0], 'password_confirmation_state'):
                this.onSetError('password_confirmation_error', null, 'password_confirmation_state');
            }
        });
      },
      
      onReset() {
        this.form.name = null;
        this.form.name_state = null;
        this.form.name_error = null;

        this.form.email = null;
        this.form.email_state = null;
        this.form.email_error = null;

        this.form.password = null;
        this.form.password_state = null;
        this.form.password_error = null;

        this.form.password_confirmation = null;
        this.form.password_confirmation_state = null;
        this.form.password_confirmation_error = null;
      }
    }
  }
</script>