<template>
    <div class="mt-5">
        <b-breadcrumb>
            <b-breadcrumb-item text="Roles" to="/roles"></b-breadcrumb-item>
            <b-breadcrumb-item text="Edit Role" active></b-breadcrumb-item>
        </b-breadcrumb>
        <b-card v-if="!processing">
            <div class="d-flex justify-content-start align-items-center">
                <b-button class="mb-2 mr-3" variant="primary" to="/roles">
                    <b-icon
                        icon="arrow-left-circle-fill"
                        aria-hidden="true"
                    ></b-icon>
                </b-button>
                <h4>Edit Role</h4>
            </div>
            <b-form @submit.prevent="onSubmit" @reset.prevent="onReset">
                <b-form-group label="Name:">
                    <b-form-input
                        v-model="form.name"
                        :state="form.name_state"
                        type="text"
                        placeholder="Enter name"
                        :disabled="submitting"
                    ></b-form-input>
                    <b-form-invalid-feedback :state="form.name_state">
                        {{ form.name_error }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <b-form-group label="Permissions:">
                    <b-form-checkbox-group :state="form.permissions_state">
                        <b-form-checkbox
                            v-for="permission in permissions"
                            :key="permission.id"
                            v-model="form.permissions"
                            :value="permission.name"
                            :disabled="submitting"
                        >
                            {{ permission.name }}
                        </b-form-checkbox>
                    </b-form-checkbox-group>
                    <!-- <b-form-select v-model="form.permissions" :options="permissions" multiple value="name"></b-form-select> -->
                    <b-form-invalid-feedback :state="form.permissions_state">
                        {{ form.permissions_error }}
                    </b-form-invalid-feedback>
                </b-form-group>

                <div>
                    <b-badge
                        v-for="(permission, index) in form.permissions"
                        :key="index"
                        class="mr-2 mb-4 px-3 py-2"
                        pill
                        variant="primary"
                    >
                        {{ permission }}
                    </b-badge>
                </div>

                <b-button v-if="!submitting" type="submit" pill variant="primary"
                    >Submit</b-button
                >
                <b-button v-else type="submit" pill variant="primary">
                    <b-spinner
                        variant="primary"
                        small
                        type="grow"
                        label="Spinning"
                    ></b-spinner>
                    Loading...
                </b-button>
                <b-button type="reset" pill variant="danger">Reset</b-button>
            </b-form>
        </b-card>
        <b-card v-else>
            <b-spinner
                variant="primary"
                type="grow"
                label="Spinning"
            ></b-spinner>
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

                permissions: [],
                permissions_state: null,
                permissions_error: null,
            },
            role: null,
            permissions: [],
        };
    },
    created() {
        this.onGetPermissions();
    },
    methods: {
        onClearErrors() {
            this.form.name_state = null;
            this.form.name_error = null;
            this.form.permissions_state = null;
            this.form.permissions_error = null;
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
        onGetPermissions() {
            this.processing = true;
            this.axios
                .get("/api/permissions/all")
                .then((response) => {
                    this.processing = false;
                    if (response.status === 200) {
                        this.permissions = response.data;
                        this.onGetRole();
                    }
                })
                .catch((error) => {
                    this.processing = false;
                    console.log(error);
                });
        },
        onGetRole() {
            this.axios
                .get(`/api/roles/${this.$route.params.id}/edit`)
                .then((response) => {
                    if (response.status === 200) {
                        this.role = response.data;
                        this.form.name = this.role.name;
                        this.form.permissions = this.role.selected_permissions;
                    }
                })
                .catch((error) => {
                    console.log(error);
                    let response = error.response;
                    if (response.status === 403) {
                        this.$swal({
                            title: "Forbidden!",
                            text: "You are not authorized to perform this action.",
                            icon: "error",
                        }).then((res) => {
                            if (res.isConfirmed) {
                                this.$router.push("/roles");
                            }
                        });
                    }
                });
        },
        onSubmit() {
            this.onClearErrors();
            this.submitting = true;
            let form = {
                name: this.form.name,
                permissions: this.form.permissions,
            };
            this.axios
                .put(`/api/roles/${this.$route.params.id}`, form)
                .then((response) => {
                    this.submitting = false;
                    let data = response.data;
                    if (response.status === 200) {
                        this.$swal({
                            title: "Success!",
                            text: data.message,
                            icon: "success",
                        }).then((res) => {
                            if (res.isConfirmed) {
                                this.$router.push("/roles");
                            }
                        });
                    }
                })
                .catch((error) => {
                    this.submitting = false;
                    console.log(error);
                    let errors = error.response.data.errors;
                    let response = error.response;
                    if (errors) {
                        errors.name
                            ? this.onSetError(
                                  "name_error",
                                  errors.name[0],
                                  "name_state"
                              )
                            : this.onSetError("name_error", null, "name_state");
                        errors.permissions
                            ? this.onSetError(
                                  "permissions_error",
                                  errors.permissions[0],
                                  "permissions_state"
                              )
                            : this.onSetError(
                                  "permissions_error",
                                  null,
                                  "permissions_state"
                              );
                    }
                    if (response.status === 403) {
                        this.$swal({
                            title: "Forbidden!",
                            text: "You are not authorized to perform this action.",
                            icon: "error",
                        }).then((res) => {
                            if (res.isConfirmed) {
                                this.$router.push("/roles");
                            }
                        });
                    }
                });
        },
        onReset() {
            this.form.name = this.role.name;
            this.form.name_state = null;
            this.form.name_error = null;

            this.form.permissions = this.role.selected_permissions;
            this.form.permissions_state = null;
            this.form.permissions_error = null;
        },
    },
};
</script>
