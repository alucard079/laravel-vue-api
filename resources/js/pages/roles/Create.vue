<template>
    <div class="mt-5">
        <b-breadcrumb>
            <b-breadcrumb-item text="Roles" to="/roles"></b-breadcrumb-item>
            <b-breadcrumb-item text="Create Role" active></b-breadcrumb-item>
        </b-breadcrumb>
        <b-card>
            <div class="d-flex justify-content-start align-items-center">
                <b-button class="mb-2 mr-3" variant="primary" to="/roles">
                    <b-icon icon="arrow-left-circle-fill" aria-hidden="true"></b-icon>
                </b-button>
                <h4>Add Role</h4>
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
                <b-form-group label="Permissions:">
                    <b-form-checkbox-group :state="form.permissions_state">
                        <b-form-checkbox
                            v-for="permission in permissions"
                            :key="permission.id"
                            v-model="form.permissions"
                            :value="permission.name"
                        >
                            {{permission.name}}
                        </b-form-checkbox>
                    </b-form-checkbox-group>
                     <b-form-invalid-feedback :state="form.permissions_state">
                        {{form.permissions_error}}
                    </b-form-invalid-feedback>
                </b-form-group>

                <div>
                    <b-badge class="mr-4 mb-2 px-3 py-2" pill variant="primary" v-for="(permission, index) in form.permissions" :key="index">
                        {{permission}}
                    </b-badge>
                </div>

                <b-button type="submit" variant="primary">Submit</b-button>
                <b-button type="reset" variant="danger">Reset</b-button>
            </b-form>
        </b-card>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                name: null,
                name_state: null,
                name_error: null,

                permissions: [],
                permissions_state: null,
                permissions_error: null,
            },
            permissions: [],
        }
    },
    created() {
        this.onGetPermissions();
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
        onGetPermissions() {
            this.axios.get('/api/permissions/all')
            .then(response => {
                if(response.status === 200) {
                    this.permissions = response.data;
                }
            })
            .catch(error => {
                console.log(error)
            });
        },
        onSubmit() {
            let form = {
                name: this.form.name,
                permissions: this.form.permissions,
            }
            this.axios.post('/api/roles', form)
            .then(response => {
                let data = response.data;
                if(response.status === 200) {
                    this.$swal({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/roles');
                        }
                    });
                }
            })
            .catch(error => {
                let errors = error.response.data.errors;
                if(errors) {
                    errors.name ?
                        this.onSetError('name_error', errors.name[0], 'name_state'):
                        this.onSetError('name_error', null, 'name_state');
                    errors.permissions ?
                        this.onSetError('permissions_error', errors.permissions[0], 'permissions_state'):
                        this.onSetError('permissions_error', null, 'permissions_state');
                }
            });
        },
        onReset() {
            this.form.name = null;
            this.form.name_state = null;
            this.form.name_error = null;

            this.form.permissions = null;
            this.form.permissions_state = null;
            this.form.permissions_error = null;
        },
    },
}
</script>