<template>
    <v-main>
        <v-container>
            <v-tabs>
                <v-tab>Table View</v-tab>
                <v-tab>Card View</v-tab>
                <v-tab-item>
                    <DataTable
                        :title="title"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        @storeUser="storeAgent($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyAgent($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"
                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :title="title"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        @storeUser="storeAgent($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyAgent($event)"
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
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Booth",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
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
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
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
                let date = '';
                let count = 0;
                this.contents = []
                for (let item in data) {
                    date = this.getDateToday(new Date(data[item].updated_at));
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
                        updated_at: date,
                    }
                    this.contents.push(agent);
                }
            }).catch(err => {
                console.log(err)
            });

        },
        async storeAgent(item) {
            const response = await axios.post('/api/v1/agents', {
                'name': item.name,
                'birthdate': item.birthdate,
                'gender': item.gender,
                'contact_number': item.contact_number,
                'email': item.email,
                'cluster_id': item.cluster.id,
                'address': this.address

            }).then(response => {
                console.log(response.status)
            }).catch(err => console.log(err))
            await this.displayUsers()
        },
        async updateUser() {

        },
        async destroyAgent(item) {
            const response = await axios.delete('agents/?'+item.id).catch(err => console.log(err))
            await this.displayAgents();
        },

        async getClusters() {
            const response = await axios.get('clusters').catch(err => console.log(err))
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

    }
}
</script>
