<template>
    <div>
        <b-row class="mt-5">
            <b-col md="12">
                <b-card v-if="!processing">
                    <b-row>
                        <b-col>
                            <div class="d-flex justify-content-between">
                                <h4>Roles</h4>
                                <b-button
                                    v-if="can('role.create')"
                                    size="sm"
                                    class="mr-1 mb-1"
                                    variant="primary"
                                    to="/roles/create"
                                >
                                    Add Role
                                </b-button>
                            </div>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-col>
                            <div class="d-flex justify-content-between">
                                <!-- <b-form-group>
                                    <b-input-group size="sm">
                                        <b-form-input
                                            v-model="search"
                                            type="text"
                                            placeholder="Search by name"
                                        >
                                        </b-form-input>
                                    </b-input-group>
                                </b-form-group> -->
                                <b-form-group>
                                    <b-form-select
                                        v-model="roles.per_page"
                                        :options="[5, 10, 15]"
                                        @change="onChangeEntries"
                                    ></b-form-select>
                                </b-form-group>
                            </div>
                        </b-col>
                    </b-row>
                    <b-table
                        id="role-table"
                        striped
                        hover
                        outlined
                        :items="roles.data"
                        :fields="table_options.fields"
                        :per-page="roles.per_page"
                        :current-page="roles.page"
                        responsive
                        show-empty
                    >
                        <template #cell(permissions)="row">
                            <div>
                                <b-badge
                                    v-for="(permission, index) in row.item
                                        .permissions"
                                    :key="index"
                                    class="mr-2 mb-4 px-3 py-2"
                                    pill
                                    variant="primary"
                                >
                                    {{ permission.name }}
                                </b-badge>
                            </div>
                        </template>
                        <template #cell(actions)="row">
                            <div class="d-flex">
                                <b-button
                                    v-if="can('role.edit')"
                                    size="sm"
                                    class="mr-1"
                                    variant="primary"
                                    :to="{
                                        name: 'roles-edit',
                                        params: { id: row.item.id },
                                    }"
                                >
                                    <b-icon
                                        icon="pencil-square"
                                        aria-hidden="true"
                                    ></b-icon>
                                </b-button>
                                <b-button
                                    v-if="can('role.delete')"
                                    size="sm"
                                    class="mr-1"
                                    variant="danger"
                                    @click="onDelete(row.item.id)"
                                >
                                    <b-icon
                                        icon="trash-fill"
                                        aria-hidden="true"
                                    ></b-icon>
                                </b-button>
                                <span
                                    v-if="
                                        !can('role.edit') && !can('role.delete')
                                    "
                                >
                                    No action Available
                                </span>
                            </div>
                        </template>
                    </b-table>
                    <b-pagination
                        v-model="roles.current_page"
                        :total-rows="roles.total"
                        :per-page="roles.per_page"
                        aria-controls="role-table"
                        @change="onChangePage"
                    ></b-pagination>
                </b-card>
                <b-card v-else>
                    <b-spinner
                        variant="primary"
                        type="grow"
                        label="Spinning"
                    ></b-spinner>
                    <span>Please wait information is loading...</span>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            processing: false,
            table_options: {
                fields: [
                    { key: "name" },
                    { key: "permissions" },
                    { key: "actions" },
                ],
            },
            // search: "",
            roles: [],
        };
    },
    created() {
        this.onGetRoles();
    },
    methods: {
        onGetRoles() {
            this.processing = true;
            this.axios
                .get("/api/roles/")
                .then((response) => {
                    this.processing = false;
                    if (response.status === 200) {
                        this.roles = response.data;
                    }
                })
                .catch((error) => {
                    this.processing = false;
                    console.log(error);
                    let response = error.response;
                    if (response.status === 403) {
                        this.$swal({
                            title: "Forbidden!",
                            text: "You are not authorized to perform this action.",
                            icon: "error",
                        }).then((res) => {
                            if (res.isConfirmed) {
                                this.$router.push("/dashboard");
                            }
                        });
                    }
                });
        },
        onChangeEntries(value) {
            this.processing = true;
            const params = {
                page: 1,
                perPage: value,
            };
            this.axios
                .get("/api/roles/", { params })
                .then((response) => {
                    this.processing = false;
                    if (response.status === 200) {
                        this.roles = response.data;
                    }
                })
                .catch((error) => {
                    this.processing = false;
                    console.log(error);
                });
        },
        onChangePage(value) {
            this.processing = true;
            const params = {
                page: value,
                perPage: this.roles.per_page,
            };
            this.axios
                .get("/api/roles/", { params })
                .then((response) => {
                    this.processing = false;
                    if (response.status === 200) {
                        this.roles = response.data;
                    }
                })
                .catch((error) => {
                    this.processing = false;
                    console.log(error);
                });
        },
        onDelete(id) {
            this.$swal({
                title: "Are you sure?",
                text: "You want to delete this record?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Delete",
            }).then((res) => {
                if (res.isConfirmed) {
                    this.processing = true;
                    this.axios
                        .delete(`/api/roles/${id}`)
                        .then((response) => {
                            this.processing = false;
                            let data = response.data;
                            if (response.status === 200) {
                                this.$swal({
                                    title: "Success!",
                                    text: data.message,
                                    icon: "success",
                                });
                                this.onGetRoles();
                            }
                        })
                        .catch((error) => {
                            this.processing = false;
                            console.log(error);
                            let response = error.response;
                            if (response.status === 403) {
                                this.$swal({
                                    title: "Forbidden!",
                                    text: "You are not authorized to perform this action.",
                                    icon: "error",
                                });
                            }
                        });
                }
            });
        },
    },
};
</script>
