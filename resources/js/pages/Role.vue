<template>
    <div>
        <v-tabs>
            <v-tab>Table View</v-tab>
            <v-tab>Card View</v-tab>
            <v-tab>Add New</v-tab>

            <v-tab-item>
                <DataTable
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
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Age", value: "age"},
            {text: "Gender", value: "gender"},
            {text: "Contact #", value: "contact_number"},
            {text: "Email", value: "email"},
            {text: "Address", value: "address"},
            {text: "Base", value: "base"},
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
            const response = await axios.get('/api/users').catch(err => {
                console.log(err)
            });
            let user = {};
            const data = response.data[0];
            let date = '';
            let count = 0;
            for (let item in data) {
                date = this.getDateToday( new Date( data[item].updated_at ) );
                count++;
                user = {
                    count: count,
                    name: data[item].name,
                    age: data[item].age,
                    gender: data[item].gender,
                    contact_number: data[item].contact_number,
                    email: data[item].email,
                    address: data[item].address.name,
                    base: data[item].base.base_name,
                    updated_at: date,
                }

                console.log( date );
                this.contents.push(user);
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
