<template>
    <div>
         <b-row class="mt-5">
            <b-col md="12">
                <b-card v-if="!processing">
                    <b-row>
                        <b-col>
                            <div class="d-flex justify-content-between">
                                <h4>Permissions</h4>
                                <b-button v-if="can('permission.create')" size="sm" class="mr-1 mb-1" variant="primary" to="/permissions/create">
                                    Add Permission
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
                                        v-model="permissions.per_page"
                                        :options="[5, 10, 15]"
                                        @change="onChangeEntries"
                                    ></b-form-select>
                                </b-form-group>
                            </div>
                        </b-col>
                    </b-row>
                    <b-table 
                        id="permission-table"
                        striped 
                        hover 
                        outlined
                        :items="permissions.data"
                        :fields="table_options.fields"
                        :per-page="permissions.per_page"
                        :current-page="permissions.page"
                        responsive
                        show-empty
                    >
                    <template #cell(actions)="row">
                        <b-button v-if="can('permission.edit')" size="sm" class="mr-1" variant="primary" :to="{ name: 'permissions-edit', params: { id: row.item.id }}">
                            <b-icon icon="pencil-square" aria-hidden="true"></b-icon>
                        </b-button>
                        <b-button v-if="can('permission.delete')" size="sm" class="mr-1" variant="danger" @click="onDelete(row.item.id)">
                            <b-icon icon="trash-fill" aria-hidden="true"></b-icon>
                        </b-button>
                        <span v-if="!can('permission.edit') && !can('permission.delete')">
                            No action available
                        </span>
                    </template>
                    </b-table>
                    <b-pagination
                        v-model="permissions.current_page"
                        :total-rows="permissions.total"
                        :per-page="permissions.per_page"
                        aria-controls="permission-table"
                        @change="onChangePage"
                    ></b-pagination>
                </b-card>
                <b-card v-else>
                    <b-spinner variant="primary" type="grow" label="Spinning"></b-spinner>
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
                fields: [{ key: 'name'}, { key: 'actions'}],
            },
            // search: "",
            permissions: []
        }
    },
    created() {
        this.onGetPermissions();
    },
    methods: {
        onGetPermissions() {
            this.processing = true;
            this.axios.get('/api/permissions/')
            .then(response => {
                this.processing = false;
                if(response.status === 200) {
                    this.permissions = response.data;
                }
            })
            .catch(error => {
                this.processing = false;
                console.log(error)
                let response = error.response;
                if(response.status === 403) {
                    this.$swal({
                        title: 'Forbidden!',
                        text: 'You are not authorized to perform this action.',
                        icon: 'error',
                    }).then((res) => {
                        if(res.isConfirmed) {
                            this.$router.push('/dashboard');
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
            }
            this.axios.get('/api/permissions/', {params})
            .then(response => {
                this.processing = false;
                if(response.status === 200) {
                    this.permissions = response.data;
                }
            })
            .catch(error => {
                this.processing = false;
                console.log(error)
            });
        },
        onChangePage(value) {
            this.processing = true;
            const params = {
                page: value,
                perPage: this.permissions.per_page,
            }
            this.axios.get('/api/permissions/', {params})
            .then(response => {
                this.processing = false;
                if(response.status === 200) {
                    this.permissions = response.data;
                }
            })
            .catch(error => {
                this.processing = false;
                console.log(error)
            });
        },
        onDelete(id) {
            this.$swal({
                title: 'Are you sure?',
                text: 'You want to delete this record?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
            }).then((res) => {
                if(res.isConfirmed) {
                    this.processing = true;
                    this.axios.delete(`/api/permissions/${id}`)
                    .then(response => {
                        this.processing = false;
                        let data = response.data;
                        if(response.status === 200) {
                            this.$swal({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                            });
                            this.onGetPermissions();
                        }
                    })
                    .catch(error => {
                        this.processing = false;
                        console.log(error)
                        let response = error.response;
                        if(response.status === 403) {
                            this.$swal({
                                title: 'Forbidden!',
                                text: 'You are not authorized to perform this action.',
                                icon: 'error',
                            });
                        }
                    });
                }
            })
        }
    },
}
</script>