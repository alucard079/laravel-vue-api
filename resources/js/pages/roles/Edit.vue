<template>
    <div class="mt-5">
        <b-breadcrumb>
            <b-breadcrumb-item text="Roles" to="/roles"></b-breadcrumb-item>
            <b-breadcrumb-item text="Edit Role" active></b-breadcrumb-item>
        </b-breadcrumb>
        <b-card>
            <div class="d-flex justify-content-start align-items-center">
                <b-button class="mb-2 mr-3" variant="primary" to="/roles">
                    <b-icon icon="arrow-left-circle-fill" aria-hidden="true"></b-icon>
                </b-button>
                <h4>Edit Role</h4>
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
            },
            role: null,
        }
    },
    created() {
        this.onGetRole();
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
        onGetRole() {
            this.axios.get(`/api/roles/${this.$route.params.id}/edit`)
            .then(response => {
                if(response.status === 200) {
                    this.role = response.data;
                    this.form.name = this.role.name
                }
            })
            .catch(error => {
                console.log(error)
            });
        },
        onSubmit() {
            let form = {
                name: this.form.name,
            }
            this.axios.put(`/api/roles/${this.$route.params.id}`, form)
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
                }
            });
        },
        onReset() {
            this.form.name = this.role.name;
            this.form.name_state = null;
            this.form.name_error = null;
        },
    },
}
</script>