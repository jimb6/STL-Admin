<template>
    <v-main>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>
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
                        @storeModel="storeDevice($event)"
                        @destoryModel="destroyDevice($event)"
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
                        @storeModel="storeDevice($event)"
                        @destroyModel="destroyDevice($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"/>
                </v-tab-item>
            </v-tabs>
            <div class="cstm-side-floating" v-show="$props.canAdd">
                <button>
                    <i class="fas fa-qrcode" @click="this.getRegistrationQr"></i></button>
                <div>
                    <h3>Register New Device</h3>
                    <v-progress-circular v-show="isQrCreating"
                                         :size="70"
                                         :width="7"
                                         color="#ffffff"
                                         indeterminate/>
                    <qrcode-vue v-if="qrValue !== ''"
                                v-show="!isQrCreating"
                                :value="qrValue"
                                :size="size"
                                background="#2196F3"
                                foreground="#1D3557"
                                level="L"/>
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
import QrcodeVue from 'qrcode.vue'
import ErrorNotif from "../components/Notification/ErrorNotif";

Vue.use(Vuetify)
export default {
    name: "Device",
    props: ['canAdd'],
    components: {
        ErrorNotif,
        Card,
        Card2,
        DataTable,
        QrcodeVue
    },
    data: () => ({
        title: "Agent Device",
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
        qrValue: '',
        size: 180,
        editedItem: {},
        address: Array,

        canAdd: true,
        canEdit: false,
        canDelete: true,

        notifications: [],

        isQrCreating: false,
        deviceId: 0,
        clusterId: 0,
    }),

    created() {
        this.errors = [];
        this.displayDevices()
        this.getRegistrationQr()
        this.listen()
    },

    methods: {

        async displayDevices() {
            await axios.get('/api/v1/devices')
                .then(response => {
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
                            agent_name: data[item].user ? data[item].user.name : "NOT ASSIGNED",
                            serial_number: data[item].device_code,
                            updated_at: date,
                        }
                        this.contents.push(device);
                    }
                }).catch(err => {
                    this.addNotification("Error fetching devices.", "error", err.status)
                });
        },

        async getRegistrationQr() {
            this.isQrCreating = true;
            const response = await axios.get('/api/v1/devices/create')
                .then(response => {
                    this.qrValue = response.data
                    console.log(this.qrValue)
                })
                .catch(err => {
                    this.errors.push({message: "Error Generating QR", error: err})
                }).finally(() => {
                    this.isQrCreating = false
                })


        },

        async storeDevice($event) {

        },

        async destroyDevice($event) {

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

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },

        async listen() {
            axios.get('/api/user').then(response => {
                Echo.channel('device-store.' + response.data.user.cluster_id)
                    .listen('NewDeviceAdded', (device) => {
                        this.getRegistrationQr();
                        this.displayDevices();
                    });
            }).catch(err => console.log(err))
        }
    }
}

$(document).ready(function () {
    $(".cstm-side-floating button").click(function () {
        $(".cstm-side-floating").toggleClass('active');
    });
});

</script>

<style scoped>
.cstm-side-floating {
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
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .05);
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
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .05);
    transition-duration: 500ms;
    font-size: 25px;
}

.cstm-side-floating.active button {
    background: #2196f3;
    color: #fff;
    box-shadow: 0 0 3px 3px rgba(0, 0, 0, .05);
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
