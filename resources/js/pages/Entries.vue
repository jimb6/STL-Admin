<template>
    <v-container>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>
        <BetsEntryDatatable
            class="singleBetTable"
            :title="game"
            :headers="headers"
            :contents="contents"
            :excelHeaders="excelHeaders"
            :excelData="excelData"
            :excelTitle="excelTitle"
        />
    </v-container>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import Notification from "../components/Notification";
import BetsEntryDatatable from "../components/BetsEntryDatatable";

export default {
    name: "Entries",
    props: {
        game: String,
    },
    components: {
        Notification,
        BetsEntryDatatable
    },
    data: () => ({
        headers: [
            {text: "Transaction Code", value: ""},
            {text: "Agent Name", value: ""},
            {text: "Device Code", value: ""},
            {text: "Draw Period", value: ""},
            {text: "Combinations", value: ""},
            {text: "Total", value: ""},
            {text: "Created At", value: ""},
            {text: "Updated At", value: ""},
            {text: "Voided", value: ""},
            {text: "Reprint", value: ""},
        ],
        contents: [],

        // Notification
        notifications: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',

    }),
    created() {
    },

    methods: {

        // FUNCTIONS HERE ==============================================================================================

        // NOTIFICATION
        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },
        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        updateExcelFields(type, dates) {
            this.excelHeaders = []
            this.excelData = []
            this.excelTitle = this.game + " (" + dates.join(" ~ ") + ")";
            this.excelHeaders = [
                {name: "Combination", subheader: []},
                {name: "Bet Amount", subheader: []},
            ];

            let fields = ["combination", "amount"]
            this.excelData.push({items: this.contents, fields: fields})
        },
    }
}
</script>
