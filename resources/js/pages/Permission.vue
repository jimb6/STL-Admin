<template>
    <div>
        <v-tabs>
            <v-tab>Table View</v-tab>

            <v-tab-item>
                <DataTable
                    :title="title"
                    :headers="headers"
                    :contents="contents"
                    :fillable="fillable"
                    @updateModel="updatePermission($event)"
                    :canEdit="true"
                />
            </v-tab-item>
        </v-tabs>

    </div>
</template>

<script>
import DataTable from "../components/DataTable";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Permission",
    props: {
        userData: JSON,
    },
    components: {
        DataTable,
    },

    data: () => ({
        title: "Permission",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Guard", value: "guard_name"},
            {text: "Last Update", value: "updated_at"},
            {text: "Roles", value: "roles"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        fillable: [
            { label: "Permission Name", field: "name", value: "", type: "input-disabled" },
            { label: "Roles", field: "roles", value: "", type: "chips-single", options: Array },
        ],
        contents: [],
    }),
    created() {
        this.getPermissions();
        this.getRoles();
    },
    methods: {
        async getPermissions() {
            const response = await axios.get('/api/v1/permissions').catch(err => {
                console.log(err)
            });
            let permission = {};
            const data = response.data.permissions;
            let date = '';
            let count = 0;
            this.contents = []; // Resetting contents to null
            for (let item in data) {
                let roles = []
                date = this.getDateToday( new Date( data[item].updated_at ) );
                count++;
                for (let roleItem in data[item].roles){
                    roles.push(data[item].roles[roleItem].name)
                }
                permission = {
                    count: count,
                    id: data[item].id,
                    name: data[item].name,
                    category: data[item].name.split(/(\s+)/)[1],
                    guard_name: data[item].guard_name,
                    updated_at: date,
                    roles: roles,
                }
                this.contents.push(permission);
            }
        },
        async updatePermission(item) {
            await axios.put('/api/v1/permissions/'+item.id, {
                roles : item.roles
            }).then(response => {
                this.getPermissions()
                }).catch(err => console.log(err))
        },
        async getRoles() {
            await axios.get('/api/v1/roles')
                .then(response => {
                    let rolesData = response.data.roles;
                    for (let index in this.fillable) {
                        if (this.fillable[index].field == 'roles') {
                            this.fillable[index].options = rolesData;
                        }
                    }
                }).catch(err => console.log(err))

        },
        getDateToday( date ) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', { month: 'long' });
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        }
    }
}
</script>
