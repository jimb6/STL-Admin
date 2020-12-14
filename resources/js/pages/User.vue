<template>
    <v-main>
        <v-container>
            <v-tabs>
                <v-tab>Table View</v-tab>
                <v-tab>Card View</v-tab>

                <v-tab-item>
                    <DataTable
                        :tableName="tableName"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                    />
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




        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "User",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
    },

    data: () => ({
        tableName: "Users",
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
        fillable: [
            {label: "Name", field: "name", value: "", type: "input"},
            {label: "Age", field: "age", value: "", type: "input"},
            {label: "Gender", field: "gender", value: "", type: "select", options: ["Male", "Female", "Others"]},
            {label: "Contact #", field: "contact_number", value: "", type: "input"},
            {label: "Email", field: "email", value: "", type: "input"},
            {label: "Address", field: "address", value: "", type: "address"},
            {label: "Base", field: "base", value: "", type: "select", options: ["Mati", "Lupon", "Baganga"]},
        ],
    }),
    created() {
        this.getUsers();
    },
    methods: {
        async getUsers() {
            const response = await axios.get('/api/users').catch(err => {
                console.log(err)
            });
            let user = {};
            const data = response.data[0];
            let date = '';
            let count = 0;
            for (let item in data) {
                date = this.getDateToday(new Date(data[item].updated_at));
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
                this.contents.push(user);
            }
        },
        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

    }
}
</script>
