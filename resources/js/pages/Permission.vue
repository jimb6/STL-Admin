<template>
    <div>
        <v-tabs>
            <v-tab>Table View</v-tab>
            <v-tab>Card View</v-tab>
            <v-tab>Add New</v-tab>

            <v-tab-item>
                <DataTable
                    :title="title"
                    :contents="contents"
                    class="elevation-1"
                    :headers="headers"/>
            </v-tab-item>
            <v-tab-item>
                <h1>CardView</h1>
            </v-tab-item>
            <v-tab-item>
                <h1>Add New</h1>
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
        title: "Permissions",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Guard", value: "guard_name"},
            {text: "Last Update", value: "updated_at"},
            {text: "Roles", value: "roles"},
        ],
        contents: [],
    }),
    created() {
        this.getPermissions();
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
            for (let item in data) {
                let roles = []
                date = this.getDateToday( new Date( data[item].updated_at ) );
                count++;
                for (let roleItem in data[item].roles){
                    roles.push(data[item].roles[roleItem].name)
                }
                permission = {
                    count: count,
                    name: data[item].name,
                    category: data[item].name.split(/(\s+)/)[1],
                    guard_name: data[item].guard_name,
                    updated_at: date,
                    roles: roles,
                }
                console.log(permission.category)
                this.contents.push(permission);
            }
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
