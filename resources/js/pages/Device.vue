<template>
    <v-main>
        <v-container>
            <div>
                <div class="col-lg-3 ">
                    <v-card>
                        <v-subheader :inset="inset">Top Games</v-subheader>

                        <v-list>
                            <template v-for="(item, index) in items">
                                <v-list-item
                                    v-if="item.action"
                                    :key="item.title"
                                    @click=""
                                >
                                    <v-list-item-action>
                                        <v-icon>{{ item.action }}</v-icon>
                                    </v-list-item-action>

                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>

                                <v-divider
                                    v-else-if="item.divider"
                                    :key="index"
                                ></v-divider>
                            </template>
                        </v-list>
                    </v-card>
                </div>
                <div class="col-lg-9 ">
                    <v-tabs>
                        <v-tab>Table View</v-tab>
                        <v-tab>Card View</v-tab>
                        <v-tab-item>
                            <DataTable
                                :tableName="tableName"
                                :contents="contents"
                                :headers="headers"
                                :fillable="fillable"
                                @storeUser="storeDevice($event)"
                                @changeAddress="changeAddress($event)"
                                @destroyUser="destroyDevice($event)"
                                :canAdd="canAdd"
                                :canEdit="canEdit"
                                :canDelete="canDelete"
                            />
                        </v-tab-item>
                        <v-tab-item>
                            <Card2
                                :tableName="tableName"
                                :contents="contents"
                                :headers="headers"
                                :fillable="fillable"
                                @storeUser="storeDevice($event)"
                                @changeAddress="changeAddress($event)"
                                @destroyUser="destroyDevice($event)"
                                :canAdd="canAdd"
                                :canEdit="canEdit"
                                :canDelete="canDelete"
                            />
                        </v-tab-item>
                    </v-tabs>
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'
import Card from "../components/Card";

Vue.use(Vuetify)
export default {
    name: "Device",
    props: {
        userData: JSON,
    },
    components: {
        Card,
        Card2,
        DataTable,
    },

    data: () => ({
        tableName: "Registered Agent Devices",
        headers: [
            {text: "#", value: "count"},
            {text: "Agent Name", value: "agent_name"},
            {text: "Serial Number", value: "serial_number"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {
                label: "Agent Name",
                field: "agent_name",
                value: "",
                type: "select",
                options: ["Male", "Female", "Others"]
            },
            {label: "Serial Number", field: "serial_number", value: "", type: "input"},
        ],

        editedItem: {},
        address: Array,

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayDevices();
        this.getUsers();
    },
    methods: {
        async displayDevices() {
            const response = await axios.get('devices/?', {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).catch(err => {
                console.log(err)
            });
            let device = {};
            const data = response.data.devices;
            let date = '';
            let count = 0;
            this.contents = []
            for (let item in data) {
                date = this.getDateToday(new Date(data[item].updated_at));
                count++;
                device = {
                    count: count,
                    id: data[item].id,
                    agent_name: data[item].user.name,
                    serial_number: data[item].serial_number,
                    updated_at: date,
                }
                this.contents.push(device);
            }
        },
        async storeDevice(item) {
            // const response = await axios.post('devices', {
            //     'name': item.name,
            //     'birthdate': item.birthdate,
            //     'gender': item.gender,
            //     'contact_number': item.contact_number,
            //     'email': item.email,
            //     'cluster_id': item.cluster.id,
            //     'address': this.address
            //
            // }).catch(err => console.log(err))
            // await this.displayUsers()
        },
        async updateDevice() {

        },
        async destroyDevice(item) {
            // const response = await axios.delete('agents/'+item.id).catch(err => console.log(err))
            // await this.displayAgents();
        },

        async getUsers() {
            // const response = await axios.get('clusters').catch(err => console.log(err))
            // let clustersData = response.data.clusters;
            // for (let index in this.fillable) {
            //     if (this.fillable[index].field == 'cluster') {
            //         this.fillable[index].options = clustersData;
            //     }
            // }
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

<style scoped>

</style>
