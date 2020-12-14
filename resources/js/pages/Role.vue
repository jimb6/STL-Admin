<template>
    <div>
        <v-tabs>
            <v-tab>Table View</v-tab>
            <v-tab>Card View</v-tab>

            <v-tab-item>
                <DataTable
                    :tableName="tableName"
                    :contents="contents"
                    :headers="headers"
                    :fillable="fillable"/>
            </v-tab-item>
            <v-tab-item>
                <Card2
                    :tableName="tableName"
                    :contents="contents"
                    :headers="headers"
                    :fillable="fillable"
                />
            </v-tab-item>
        </v-tabs>

    </div>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
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
        Card2
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
        fillable: [
            {label: "Name", field: "name", value:""},
            {label: "Age", field: "age", value:""},
            {label: "Guard", field: "guard_name", value:""},
        ]
    }),
    created() {
        this.getRoles();
    },
    methods: {
        async getRoles() {
            const response = await axios.get('roles/?').catch(err => {
                console.log(err)
            });
            console.log(response);
            let role = {};
            const data = response.data.roles;
            console.log(data);
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
