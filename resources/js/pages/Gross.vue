<template>
    <v-container>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>

        <BetsGrossDatatable
            class="singleBetTable"
            :headers="headers"
            :contents="contents"
            :excelHeaders="excelHeaders"
            :excelData="excelData"
            :excelTitle="excelTitle"
            @displayReports="displayReports($event)"
        />
    </v-container>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import Notification from "../components/Notification";
import BetsGrossDatatable from "../components/BetsGrossDatatable";

export default {
    name: "Gross",
    components: {
        Notification,
        BetsGrossDatatable
    },
    data: () => ({
        title: "Game Gross",
        contents: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',

        // Notification
        notifications: [],
    }),
    created() {
    },


    computed: {
        headers() {
            return [
                {text: "Game", value: "cluster"},
                {text: "Draw Date", value: "draw_date"},
                {text: "Draw Period", value: "draw_period"},
                {text: "Gross", value: "gross"},
                {text: "Commission", value: "commission"},
                {text: "Net", value: "net"},
                {text: "Hits", value: "hits"},
                {text: "Amount Hits", value: "amount_hits"},
                {text: "Collectible", value: "collectible"},
            ]
        }
    },

    methods: {

        displayReports(config) {
            console.log(config)
        },

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

    }
}
</script>
