<template>
    <div class="mt-5">
        <b-breadcrumb>
            <b-breadcrumb-item text="Users" to="/users"></b-breadcrumb-item>
            <b-breadcrumb-item text="Create User" active></b-breadcrumb-item>
        </b-breadcrumb>
        <b-card v-if="!processing">
            <div class="d-flex justify-content-start align-items-center">
                <b-button class="mb-2 mr-3" variant="primary" to="/users">
                    <b-icon icon="arrow-left-circle-fill" aria-hidden="true"></b-icon>
                </b-button>
                <h4>Add User</h4>
            </div>
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

                <b-form-group label="Role:">
                    <b-form-select 
                        v-model="form.role" 
                        :options="roles" 
                        :state="form.role_state"
                        text-field="name"
                    >
                        <template #first>
                            <b-form-select-option :value="null" disabled> Please select an option </b-form-select-option>
                        </template>
                    </b-form-select>

                    <b-form-invalid-feedback :state="form.role_state">
                        {{form.role_error}}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-button v-if="!submitting" type="submit" variant="primary">Submit</b-button>
                <b-button v-else type="submit" variant="primary">
                    <b-spinner variant="primary" small type="grow" label="Spinning"></b-spinner>
                    Loading...
                </b-button>
                <b-button type="reset" variant="danger">Reset</b-button>
            </b-form>
        </b-card>
        <b-card v-else>
            <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
            <span>Please wait information is loading...</span>
        </b-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            processing: false,
            submitting: false,
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

                role: null,
                role_state: null,
                role_error: null,
            },
            roles: [],
        }
    },
    created() {
        this.onGetRoles();
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
        onGetRoles() {
            this.axios.get('/api/roles/all')
            .then(response => {
                if(response.status === 200) {
                    this.roles = response.data;
                    this.onGetUser();
                }
            })
            .catch(error => {
                console.log(error)
            });
        },
        onGetUser() {
            this.processing = true;
            this.axios.get(`/api/users/create`)
            .then(response => {
                this.processing = false;
            })
            .catch(error => {
                this.processing = false;
                console.log(error);
                let response = error.response;
                if(response.status === 403) {
                    this.$swal({
                        title: 'Forbidden!',
                        text: 'You are not authorized to perform this action.',
                        icon: 'error',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/users');
                        }
                    });
                }
            });
        },
        onSubmit() {
            this.submitting = true;
            let form = {
                name: this.form.name,
                email: this.form.email,
                password: this.form.password,
                password_confirmation: this.form.password_confirmation,
                role: this.form.role,
            }
            this.axios.post('/api/users', form)
            .then(response => {
                this.submitting = false;
                let data = response.data;
                if(response.status === 200) {
                    this.$swal({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/users');
                        }
                    });
                }
            })
            .catch(error => {
                this.submitting = false;
                console.log(error)
                let errors = error.response.data.errors;
                let response = error.response;
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
                    errors.role ?
                        this.onSetError('role_error', errors.role[0], 'role_state'):
                        this.onSetError('role_error', null, 'role_state');
                }
                if(response.status === 403) {
                    this.$swal({
                        title: 'Forbidden!',
                        text: 'You are not authorized to perform this action.',
                        icon: 'error',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/users');
                        }
                    });
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

            this.form.role = null;
            this.form.role_state = null;
            this.form.role_error = null;
        },
    },
}
</script>