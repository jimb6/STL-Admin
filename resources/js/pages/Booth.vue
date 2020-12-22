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
        title: "Cluster",
        headers: [
            {text: "#", value: "count"},
            {text: "Name", value: "name"},
            {text: "Type", value: "cluster_type"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Name", field: "name", value: "", type: "input"},
            {label: "Type", field: "cluster_type", value: "", type: "select", options: ['SubMain']},
        ],

        editedItem: {},
        address: Array,

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayClusters();
    },
    methods: {
        async displayClusters() {
            await axios.get('/api/v1/clusters').then(response => {
                console.log(response)
                let cluster = {};
                const data = response.data.clusters;
                let count = 0;
                this.contents = []
                for (let item in data) {
                    count++;
                    cluster = {
                        count: count,
                        id: data[item].id,
                        name: data[item].name,
                        cluster_type: data[item].cluster_type,
                    }
                    this.contents.push(cluster);
                }
            }).catch(err => {
                console.log(err)
            });

        },
        async storeCluster(item) {
            const response = await axios.post('/api/v1/clusters', {
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
        async destroCluster(item) {
            await axios.delete('/api/v1/clusters/' + item.id)
                .then(response => {
                    this.displayAgents();
                }).catch(err => console.log(err))

        },

    }
}
</script>
