<template>
    <div>
         <b-row class="mt-5">
            <b-col md="12">
                <b-card>
                    <b-row>
                        <b-col>
                            <div class="d-flex justify-content-between">
                                <h4>Roles</h4>
                                <b-button size="sm" class="mr-1 mb-1" variant="primary" to="/roles/create">
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
                    <template #cell(actions)="row">
                        <b-button size="sm" class="mr-1" variant="primary" :to="{ name: 'roles-edit', params: { id: row.item.id }}">
                            <b-icon icon="pencil-square" aria-hidden="true"></b-icon>
                        </b-button>
                        <b-button size="sm" class="mr-1" variant="danger" @click="onDelete(row.item.id)">
                            <b-icon icon="trash-fill" aria-hidden="true"></b-icon>
                        </b-button>
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
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            table_options: {
                fields: [{ key: 'name'}, { key: 'actions' }],
            },
            // search: "",
            roles: []
        }
    },
    created() {
        this.onGetRoles();
    },
    methods: {
        onGetRoles() {
            this.axios.get('/api/roles/')
            .then(response => {
                if(response.status === 200) {
                    this.roles = response.data;
                }
            })
            .catch(error => {
                console.log(error)
            });
        },
        onChangeEntries(value) {
            const params = {
                page: 1,
                perPage: value,
            }
            this.axios.get('/api/roles/', {params})
            .then(response => {
                if(response.status === 200) {
                    this.roles = response.data;
                }
            })
            .catch(error => {
                console.log(error)
            });
        },
        onChangePage(value) {
            const params = {
                page: value,
                perPage: this.roles.per_page,
            }
            this.axios.get('/api/roles/', {params})
            .then(response => {
                if(response.status === 200) {
                    this.roles = response.data;
                }
            })
            .catch(error => {
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
                    this.axios.delete(`/api/roles/${id}`)
                    .then(response => {
                        let data = response.data;
                        if(response.status === 200) {
                            this.$swal({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                            });
                            this.onGetRoles();
                        }
                    })
                    .catch(error => {
                        console.log(error)
                    });
                }
            })
        }
    },
}
</script>