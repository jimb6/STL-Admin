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
            <div class="cstm-side-floating">
                <button><i class="fas fa-qrcode"></i></button>
                <div>
                    <h3>Register New Device</h3>
                    <img src="https://www.iconsdb.com/icons/preview/white/qr-code-xxl.png" alt="">
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
$(document).ready(function () {
    $(".cstm-side-floating button").click(function () {
        $(".cstm-side-floating").toggleClass('active');
    });
});
</script>

<style scoped>
.cstm-side-floating{
    position: fixed;
    top: 50%;
    right: -280px;
    transform: translateY(-50%);
    z-index: 1000;
    padding: 50px;
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
    transition-duration: 500ms;
    background: #2196f3;
}
.cstm-side-floating.active {
    transform: translateY(-50%) translateX(-280px) !important;
    box-shadow: 0 0 3px 3px rgba(0,0,0,.05);
}
.cstm-side-floating button {
    position: absolute;
    top: 50%;
    left: -50px;
    transform: translateY(-50%);
    background: #fff;
    color: #2196f3;
    width: 50px;
    height: 50px;
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
    outline: unset;
    box-shadow: 0 0 3px 3px rgba(0,0,0,.05);
    transition-duration: 500ms;
    font-size: 25px;
}
.cstm-side-floating.active button {
    background: #2196f3;
    color: #fff;
    box-shadow: 0 0 3px 3px rgba(0,0,0,.05);
}
.cstm-side-floating img {
    width: 180px;
}
.cstm-side-floating h3 {
    font-size: 12px;
    text-transform: uppercase;
    color: #fff;
    margin-bottom: 20px;
    letter-spacing: 2px;
    text-align: center;
    font-weight: 600;
}
</style>
