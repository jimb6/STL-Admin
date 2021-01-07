<template>
    <v-main>
        <v-container>
            <DataTable
                :title="title"
                :headers="headers"
                :contents="contents"
                :fillable="fillable"
                @storeModel="storeCluster($event)"
                @updateModel="updateCluster($event)"
                :canAdd="canAdd"
                :canEdit="canEdit"
                :canDelete="canDelete"
                :excelHeaders="excelHeaders"
                :excelData="excelData"
                :excelTitle="title"
            />
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'

export default {
    name: "Cluster",
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
            {text: "No. of Agents", value: "agents"},
            {text: "Commissions", value: "commissions"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Name", field: "name", value: "", type: "input"},
            {label: "Type", field: "cluster_type", value: "", type: "select", options: ['Sub-Main']},
            {label: "Commission Values", field: "commission_vals", value: "", type: "hidden"},
        ],

        editedItem: {},
        address: Array,

        canAdd: true,
        canEdit: true,
        canDelete: true,

        json_fields: {
            "Cluster Name": "name",
            "Type": "type",
            "No. of Agents": "city",
            "Commissions": "",
        },

        excelHeaders: [],
        excelData: [],
    }),
    created() {
        this.displayClusters();
    },
    methods: {
        async displayClusters() {
            await axios.get('/api/v1/clusters').then(response => {
                const data = response.data.clusters;
                let count = 0;
                this.contents = []
                for (let item in data) {                                                                        // Every Cluster
                    let commissions = [];
                    count++;
                    let cluster = {
                        count: count,
                        id: data[item].id,
                        name: data[item].name,
                        cluster_type: data[item].cluster_type,
                        agents: data[item].agents.length,
                        commissions: [],
                    }
                    for (let i in data[item].commissions) {                                         //Every game commission
                        cluster[data[item].commissions[i].game.abbreviation] = data[item].commissions[i].commission_rate * 100
                        cluster.commissions.push(
                            {
                                label: data[item].commissions[i].game.abbreviation,
                                value: data[item].commissions[i].commission_rate * 100
                            }
                        )
                    }
                    this.contents.push(cluster);
                }
                this.getGameCommissions();

            }).catch(err => {
                console.log(err)
            });
        },

        async getGameCommissions() {
            axios.get('/api/v1/games')
                .then(response => {
                    this.games = [];
                    let data = response.data.games;
                    this.fillable = [
                        {label: "Name", field: "name", value: "", type: "input"},
                        {label: "Type", field: "cluster_type", value: "", type: "select", options: ['Sub-Main']},
                        {label: "Commission Values", field: "commission_vals", value: "", type: "hidden"},
                    ]
                    for (let index in data) {
                        this.games.push(data[index].abbreviation)
                        this.fillable.push({
                            label: data[index].description + " (%)",
                            field: data[index].abbreviation,
                            value: "",
                            type: "group-input"
                        })
                    }
                    this.updateExcelFields()
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully added!", "error", "400");
                });
        },

        async storeCluster(item) {
            await axios.post('/api/v1/clusters', item).then(response => {
                console.log(response.status)
                this.displayClusters();
            }).catch(err => console.log(err))
        },
        async updateCluster(item) {
            await axios.put('/api/v1/clusters/' + item.id, item).then(response => {
                console.log(response)
                this.displayClusters();
            }).catch(err => console.log(err))
        },
        async destroyCluster(item) {
            await axios.delete('/api/v1/clusters/' + item.id)
                .then(response => {
                    this.displayAgents();
                }).catch(err => console.log(err))

        },

        updateExcelFields() {
            this.excelHeaders = []
            this.excelData = []
            this.excelHeaders = [
                {name: "Name", subheader: []},
                {name: "Type", subheader: []},
                {name: "No. of Agents", subheader: []},
                {name: "Commissions", subheader: this.games},
            ];

            let fields = ["name", "cluster_type", "agents"]
            for (let i in this.games) {
                console.log(this.games[i], " GAME CONSOLE")
                fields.push(this.games[i])
            }
            this.excelData.push({items: this.contents, fields: fields})
        },

    }
}
</script>

<style scoped>

</style>
