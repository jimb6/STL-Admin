<template>
    <div>
        <v-tabs>
            <v-tab>Table View</v-tab>
            <v-tab>Card View</v-tab>
            <v-tab>Add New</v-tab>

            <v-tab-item>
                <DataTable
                    :tableName="tableName"
                    :contents="contents"
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
    name: "Role",
    props: {
        userData: JSON,
    },
    components: {
        DataTable,
    },

    data: () => ({
        tableName: "Roles",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Guard", value: "guard_name"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
    }),
    created() {
        this.getRoles();
    },
    methods: {
        async getRoles() {
            const response = await axios.get('/api/roles').catch(err => {
                console.log(err)
            });
            let role = {};
            const data = response.data[0].data;
            let date = '';
            let count = 0;
            for (let item in data) {
                date = this.getDateToday( new Date( data[item].updated_at ) );
                count++;
                role = {
                    count: count,
                    name: data[item].name,
                    guard_name: data[item].guard_name,
                    updated_at: date,
                }
                this.contents.push(role);
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
