<template>
    <v-main>
        <v-container>
            <div v-if="notifications.length > 0" v-for="notification in notifications">
                <Notification :notification="notification"></Notification>
            </div>
            <DataTable
                :title="title"
                :contents="contents"
                :headers="headers"
                :fillable="fillable"
                @storeModel="storeAgent($event)"
                @updateModel="updateAgent($event)"
                @destroyModel="destroyAgent($event)"
                @changeAddress="changeAddress($event)"
                @updateStatus="deactivateUser($event)"
                :canAdd="canAdd"
                :canEdit="canEdit"
                :canDelete="canDelete"
            />
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
    name: "Agent",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
        Notification
    },

    data: () => ({
        title: "Agent",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Birthdate", value: "birthdate"},
            {text: "Gender", value: "gender"},
            {text: "Contact #", value: "contact_number"},
            {text: "Email", value: "email"},
            {text: "Address", value: "address"},
            {text: "Cluster", value: "cluster"},
            {text: "Active", value: "isClosed"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Name", field: "name", value: "", type: "input"},
            {label: "Birthdate", field: "birthdate", value: "", type: "datepicker"},
            {label: "Gender", field: "gender", value: "", type: "select", options: ["Male", "Female", "Others"]},
            {label: "Contact #", field: "contact_number", value: "", type: "input-phone"},
            {label: "Email", field: "email", value: "", type: "input"},
            {label: "Address", field: "address", value: "", type: "address"},
            {label: "Cluster", field: "clusterId", value: "", type: "select", options: Array},
        ],

        editedItem: {},
        address: Array,
        notifications: [],

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayAgents();
        this.getClusters();
    },
    methods: {
        async displayAgents() {
            await axios.get('/api/v1/agents').then(response => {
                let agent = {};
                const data = response.data.agents;
                let count = 0;
                this.contents = []
                for (let item in data) {
                    count++;
                    agent = {
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
                        clusterId: data[item].cluster.id,
                        updated_at: data[item].updated_at,
                        isClosed: data[item].status,
                    }
                    this.contents.push(agent);
                }
            }).catch(err => {
                this.addNotification("Failed to load " + this.title + "s", "error", "400");
            });
        },


        async storeAgent(item) {
            await axios.post('/api/v1/agents', {
                'name': item.name,
                'birthdate': item.birthdate,
                'gender': item.gender,
                'contact_number': item.contact_number,
                'email': item.email,
                'cluster_id': item.clusterId.id,
                'address': this.address
            }).then(response => {
                console.log(response)
                this.addNotification(item.name + " added successfully!", "success", "200");
                this.displayAgents()
            }).catch(err => {
                this.addNotification(err.response.data.message, "error", "400");
            })
            console.log(item)

        },
        async updateAgent(item) {
            console.log('UPDATING...', item)
            await axios.put('/api/v1/agents/' + item.id, {
                'name': item.name,
                'birthdate': item.birthdate,
                'gender': item.gender,
                'contact_number': item.contact_number,
                'email': item.email,
                'cluster_id': item.clusterId,
                'address': this.address
            })
                .then(response => {
                    console.log(response)
                    this.displayAgents();
                })
                .catch(err => {
                    console.log(err)
                    this.addNotification(item.name + " unsuccessfully deleted!", "error", "400")
                })
        },
        async destroyAgent(item) {
            await axios.delete('/api/v1/agents/' + item.id)
                .then(response => {
                    this.addNotification(item.name + " deleted successfully!", "success", "200")
                    this.displayAgents();
                })
                .catch(err => {
                    this.addNotification(item.name + " unsuccessfully deleted!", "error", "400")
                })
        },

        async getClusters() {
            const response = await axios.get('/api/v1/clusters').catch(err => console.log(err))
            let clustersData = response.data.clusters;
            console.log(clustersData);
            for (let index in this.fillable) {
                if (this.fillable[index].field === 'clusterId') {
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

        async deactivateUser(item) {
            console.log(item, "DEACTIVATE <<<<<<<<<")
            axios.put('/api/v1/deactivate-agent/' + item.id, {
                status: item.isClosed
            })
                .then(response => {
                    this.displayAgents();
                    console.log(response);
                }).catch(err => {
                    console.log(err)
                this.$nextTick(function () {
                    this.contents[item.index].isClosed = !item.isClosed;
                });
            })
        },

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        }

    }
}
</script>
