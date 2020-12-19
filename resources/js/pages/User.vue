<template>
    <v-main>
        <v-container>
            <div v-if="notifications.length > 0" v-for="notification in notifications">
                <Notification :notification="notification"></Notification>
            </div>
            <v-tabs>
                <v-tab>Table View</v-tab>
                <v-tab>Card View</v-tab>
                <v-tab-item>
                    <DataTable
                        :title="title"
                        :headers="headers"
                        :contents="contents"
                        :fillable="fillable"
                        @storeModel="storeUser($event)"
                        @updateModel="updateUser($event)"
                        @destroyModel="destroyUser($event)"
                        @changeAddress="changeAddress($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"
                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :title="title"
                        :headers="headers"
                        :contents="contents"
                        :fillable="fillable"
                        @storeModel="storeUser($event)"
                        @updateModel="updateUser($event)"
                        @destroyModel="destroyUser($event)"
                        @changeAddress="changeAddress($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"
                    />
                </v-tab-item>
            </v-tabs>
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Notification from "../components/Notification";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "User",
    components: {
        DataTable,
        Card2,
        Notification,
    },
    data: () => ({
        title: "User",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Birthdate", value: "birthdate"},
            {text: "Gender", value: "gender"},
            {text: "Contact #", value: "contact_number"},
            {text: "Email", value: "email"},
            {text: "Address", value: "address"},
            {text: "Cluster", value: "cluster"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Roles", field: "roles", value: "", type: "select", options: Array},
            {label: "Name", field: "name", value: "", type: "input"},
            {label: "Birthdate", field: "birthdate", value: "", type: "datepicker"},
            {label: "Gender", field: "gender", value: "", type: "select", options: ["Male", "Female", "Others"]},
            {label: "Contact #", field: "contact_number", value: "", type: "input"},
            {label: "Email", field: "email", value: "", type: "input"},
            {label: "Address", field: "address", value: "", type: "address"},
            {label: "Cluster", field: "cluster", value: "", type: "select", options: Array},
        ],
        editedItem: {},
        address: Array,
        notifications: [],

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayUsers();
        this.getClusters();
        this.getRoles();
    },
    methods: {
        async displayUsers() {
            const response = await axios.get('users/?')
                .then(response=>{
                    let user = {};
                    const data = response.data.users;
                    let date = '';
                    let count = 0;
                    this.contents = []; // Resetting contents to null
                    for (let item in data) {
                        date = this.getDateToday(new Date(data[item].updated_at));
                        count++;
                        user = {
                            count: count,
                            id: data[item].id,
                            name: data[item].name,
                            birthdate: data[item].birthdate,
                            gender: data[item].gender,
                            contact_number: data[item].contact_number,
                            email: data[item].email,
                            address: data[item].address.street
                                + ", " + data[item].address.barangay
                                + ", " + data[item].address.municipality
                                + ", " + data[item].address.province,
                            cluster: data[item].cluster.name,
                            updated_at: date,
                        }
                        this.contents.push(user);
                    }
                })
                .catch(err => {
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        async storeUser(item) {
            const response = await axios.post('users/?', {
                'roles': item.roles,
                'name': item.name,
                'birthdate': item.birthdate,
                'gender': item.gender,
                'contact_number': item.contact_number,
                'email': item.email,
                'cluster_id': item.cluster.id,
                'address': this.address
            })
                .then(response => {
                    this.addNotification(item.name + " added successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.name + " unsuccessfully added!", "error", "400");
                });
            await this.displayUsers();
        },

        async updateUser() {

        },

        async destroyUser(item) {
            const response = await axios.delete('users/' + item.id)
                .then(response => {
                    this.addNotification(item.name + " deleted successfully!", "success", "200")
                })
                .catch(err => {
                    this.addNotification(item.name + " unsuccessfully deleted!", "error", "400")
                });
            await this.displayUsers();
        },

        async getRoles() {
            const response = await axios.get('roles/?').catch(err => console.log(err))
            let rolesData = response.data.roles;
            for (let index in this.fillable) {
                if (this.fillable[index].field == 'roles') {
                    this.fillable[index].options = rolesData;
                }
            }
        },

        async getClusters() {
            const response = await axios.get('clusters/?').catch(err => console.log(err))
            let clustersData = response.data.clusters;
            for (let index in this.fillable) {
                if (this.fillable[index].field == 'cluster') {
                    this.fillable[index].options = clustersData;
                }
            }
        },

        changeAddress(address) {
            this.address = address;
        },

        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        addNotification(message, type, statusCode){
            this.notifications.push({ message: message, type: type, statusCode: statusCode });
        }

    }
}
</script>
