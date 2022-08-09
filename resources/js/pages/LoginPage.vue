<template>
    <div>
        <div class="auth-container">
            <div class="auth-box">
                <p class="auth-header">Login</p>
                <b-form @submit.prevent="onSubmit" @reset.prevent="onReset">
                    <b-form-group label="Email address:">
                        <b-form-input
                            v-model="form.email"
                            :state="form.email_state"
                            type="text"
                            placeholder="Enter email"
                            :disabled="processing"
                        ></b-form-input>
                        <b-form-invalid-feedback :state="form.email_state">
                            {{ form.email_error }}
                        </b-form-invalid-feedback>
                    </b-form-group>

                    <b-form-group label="Password:">
                        <b-form-input
                            v-model="form.password"
                            type="password"
                            :state="form.password_state"
                            placeholder="Enter password"
                            :disabled="processing"
                        ></b-form-input>

                        <b-form-invalid-feedback :state="form.password_state">
                            {{ form.password_error }}
                        </b-form-invalid-feedback>
                    </b-form-group>

                    <b-button v-if="!processing" type="submit" variant="primary"
                        >Submit</b-button
                    >
                    <b-button v-else type="submit" variant="primary">
                        <b-spinner
                            variant="primary"
                            small
                            type="grow"
                            label="Spinning"
                        ></b-spinner>
                        Loading...
                    </b-button>
                    <b-button type="reset" variant="danger">Reset</b-button>
                    <b-button to="/register" variant="info">Register</b-button>
                </b-form>
            </div>
        </div>
    </div>
</template>

<script>
import Auth from "../Auth.js";
export default {
    data() {
        return {
            processing: false,
            form: {
                email: null,
                email_state: null,
                email_error: null,

                password: null,
                password_state: null,
                password_error: null,
            },
        };
    },
    methods: {
        onClearErrors() {
            this.form.email_state = null;
            this.form.email_error = null;
            this.form.password_state = null;
            this.form.password_error = null;
        },
        onSetError(vmodel_error, value, state) {
            if (value) {
                this.form[vmodel_error] = value;
                this.form[state] = value ? false : true;
            } else {
                this.form[vmodel_error] = value;
                this.form[state] = value === null ? true : false;
            }
        },
        onSubmit() {
            this.onClearErrors();
            this.processing = true;
            let form = {
                email: this.form.email,
                password: this.form.password,
            };

            this.axios
                .post("/api/login", form)
                .then((response) => {
                    this.processing = false;
                    let data = response.data;
                    if (response.status === 200) {
                        this.$appEvents.$emit("logged-on");
                        Auth.login(data.access_token, data.user); //set local
                        this.$router.push("/dashboard");
                    }
                })
                .catch((error) => {
                    this.processing = false;
                    let errors = error.response.data.errors;
                    if (errors) {
                        errors.email
                            ? this.onSetError(
                                  "email_error",
                                  errors.email[0],
                                  "email_state"
                              )
                            : this.onSetError(
                                  "email_error",
                                  null,
                                  "email_state"
                              );
                        errors.password
                            ? this.onSetError(
                                  "password_error",
                                  errors.password[0],
                                  "password_state"
                              )
                            : this.onSetError(
                                  "password_error",
                                  null,
                                  "password_state"
                              );
                    }

                    if (
                        error.response.status === 401 ||
                        error.response.status === 400
                    ) {
                        this.$swal({
                            title: "Error!",
                            text: error.response.data.error,
                            icon: "error",
                        });
                    }
                });
        },

        onReset() {
            this.form.email = null;
            this.form.email_state = null;
            this.form.email_error = null;

            this.form.password = null;
            this.form.password_state = null;
            this.form.password_error = null;
        },
    },
};
</script>
