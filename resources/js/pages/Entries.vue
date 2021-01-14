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
            :reportsUrl="reportsUrl"
            @viewModel="displayBetEntries($event)"
            @updateRealtimeStatus="updateRealTimeStatus($event)"
            @updateVoidStatus="updateVoidStatus($event)"
            @updateStatus="updatePrintableStatus($event)"
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
        contents: [],

        // Notification
        notifications: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',

        drawPeriodFilter: {},

        reportsUrl: '',
        lastItem: {}

    }),


    created() {
        this.getDrawPeriods();
        this.listen();
    },

    computed: {
        headers () {
            return [
                {text: "Transaction Code", value: "transaction_code"},
                {text: "Agent Name", value: "user_name"},
                {text: "Draw Period", value: "draw_period"},
                {text: "Combinations", value: "combinations"},
                {text: "Total", value: "total"},
                {text: "Created At", value: "created_at"},
                {text: "Updated At", value: "updated_at"},
                {text: "Reprint", value: "printable"},
                {text: "Void", value: "is_void"},
            ]
        },
    },

    methods: {

        // FUNCTIONS HERE ==============================================================================================

        async displayBetEntries(item) {
            this.lastItem = item
            console.log(item, "ITEMS")
            axios.post('/api/v1/bet-transaction-entries', {
                dates: item[1],
                draw_periods: item[0].selected.value,
                game: this.game
            })
                .then(response => {
                    const data = response.data.transactions;
                    this.contents = [];
                    this.reportsUrl = response.data.report_url
                    for (let item in data) {
                        this.contents.push({
                            id: data[item].id,
                            transaction_code: data[item].qr_code,
                            user_name: data[item].name,
                            draw_period: data[item].draw_time,
                            combinations: data[item].combinations,
                            total: data[item].total,
                            created_at: this.getDateToday(new Date(data[item].created_at)),
                            updated_at: this.getDateToday(new Date(data[item].updated_at)),
                            printable: data[item].printable,
                            void_status: data[item].is_void
                        });
                    }
                    console.log("URL:: ", this.reportsUrl)
                    console.log(response)
                }).catch(err => {
                console.log(err)
            })
        },

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },
        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },


        getCurrentDate() {
            let date = new Date();
            const month = date.toLocaleString('default', {month: 'numeric'});
            date = date.getFullYear() + "-" + (month < 10 ? "0" + month : month) + "-" + (date.getDate() < 10 ? "0" + date.getDate() : date.getDate());
            return date
        },

        getDrawPeriods() {
            axios.get('/api/v1/draw-periods-categorized/' + this.game)
                .then(response => {
                    let drawPeriods = response.data.drawPeriods
                    if (drawPeriods.length > 1){
                        let ids = []
                        for (let item in drawPeriods) {
                            ids.push(drawPeriods[item].id)
                        }
                        this.drawPeriodFilter = {
                            selected: {text: "All", value: ids},
                            options: [{text: "All", value: ids}],
                        }
                        for (let item in drawPeriods) {
                            let drawTime = new Date('1/1/2021 ' + drawPeriods[item].draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                            this.drawPeriodFilter.options.push({
                                text: drawTime,
                                value: [drawPeriods[item].id]
                            })
                        }
                    } else {
                        let drawTime = new Date('1/1/2021 ' + drawPeriods[0].draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                        this.drawPeriodFilter = {
                            selected: {text: drawTime, value: [drawPeriods[0].id]},
                            options: [{text: drawTime, value: [drawPeriods[0].id]}],
                        }
                    }
                    this.displayBetEntries([this.drawPeriodFilter, [this.getCurrentDate(), this.getCurrentDate()]]);
                }).catch(err => {
                console.log(err)
            })
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
        async updatePrintableStatus(item) {
            axios.put('/api/v1/bet-transaction-printable/' + item.id, {
                printable: item.printable
            })
                .then(response => {
                    console.log(response);
                }).catch(err => {
                this.$nextTick(function () {
                    this.contents[item.index].printable = !item.printable;
                });
            })
        },

        updateRealTimeStatus(item){
            if(item) this.listen()
            else this.ignore()
        },

        updateVoidStatus(item){
            console.log("VOID STATUS", item)
        },

        async listen() {
            Echo.channel('bets.' + this.game)
                .listen('NewBetTransactionAdded', (data) => {
                    this.displayBetEntries(this.lastItem);
                    console.log("NEW BET EVENT")
                })
        },

        ignore() {
            Echo.leaveChannel('bets.' + this.game);
        }
    }
}
</script>
