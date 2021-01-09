<template>
    <v-container>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>
        <v-alert
            v-show="error"
            dense
            border="left"
            type="error">
            Hoy yagpataka kaw.!
        </v-alert>
        <BetsWinningCombinationDatatable
            class="singleBetTable"
            :title="game"
            :headers="headers"
            :contents="contents"
            :fillable="fillable"
            :excelHeaders="excelHeaders"
            :excelData="excelData"
            :excelTitle="excelTitle"
            @displayBetWinningCombinations="displayBetWinningCombinations($event)"
            @storeWinningCombination="storeWinningCombination($event)"
            @verifyWinningCombination="verifyWinningCombination($event)"
        />
    </v-container>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import Notification from "../components/Notification";
import BetsWinningCombinationDatatable from "../components/BetsWinningCombinationDatatable";

export default {
    name: "Entries",
    props: {
        game: String,
    },
    components: {
        Notification,
        BetsWinningCombinationDatatable
    },
    data: () => ({
        headers: [
            {text: "Combinations", value: "combination"},
            {text: "Draw Period", value: "drawPeriod"},
            {text: "Draw Date", value: "drawDate"},
            {text: "Verified", value: "verifiedAt"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Combinations", field: "combination", value: "", type: "input"},
            {label: "Draw Period", field: "drawPeriodId", value: "", type: "select", options: Array},
            {label: "Password", field: "password", value: "", type: "input-password"},
        ],

        // Notification
        notifications: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',
        error: false,

    }),
    created() {
        this.getDrawPeriods();
        this.displayBetWinningCombinations([this.getCurrentDate(), this.getCurrentDate()]);
    },

    methods: {

        // FUNCTIONS HERE ==============================================================================================
        async displayBetWinningCombinations(dates) {
            axios.post('/api/v1/winning-combinations', {
                'game': this.game,
                'dates': dates
            }).then(response => {
                const data = response.data.winningCombination;
                this.contents = [];
                for (let item in data) {
                    this.contents.push({
                        id: data[item].id,
                        combination: data[item].combination,
                        drawPeriod: data[item]['draw_period'].draw_time,
                        drawPeriodId: data[item]['draw_period'].id,
                        drawDate: data[item].created_at,
                        verifiedAt: data[item].verified_at,
                    });
                }
            }).catch(err => console.log(err))
        },

        async storeWinningCombination(item) {
            axios.post('/api/v1/winning-combinations-store', {
                'combination': item.combination,
                'password': item.password,
                'game': this.game,
                'drawPeriodId': item.drawPeriodId
            }).then(response => {
                this.displayBetWinningCombinations([this.getCurrentDate(), this.getCurrentDate()]);
            }).catch(err => {
                this.error = true
            })
        },

        async updateWinningCombination(item) {
            axios.put('/api/v1/winning-combinations-update/' + item.id, {
                'combination': item.combination,
                'password': item.password,
                'game': this.game,
                'drawPeriodId': item.drawPeriodId
            }).then(response => {
                this.displayBetWinningCombinations([this.getCurrentDate(), this.getCurrentDate()]);
            }).catch(err => {
                this.error = true
            })
        },

        async verifyWinningCombination(item) {
            console.log(item)
            if (item.combination !== item.verifiedItem.combination || item.drawPeriodId !== item.verifiedItem.drawPeriodId) {
                console.log("error")
                return
            }
            console.log('asldkjfadklsfklsdfjl')
            axios.put('/api/v1/winning-combinations-verify/' + item.id, {
                'password': item.password,
            })
                .then(response => {
                    console.log(response)
                    this.displayBetWinningCombinations([this.getCurrentDate(), this.getCurrentDate()]);
                }).catch(err => {
                    console.log(err)
                this.error = true
            })

        },

        getDrawPeriods() {
            axios.get('/api/v1/draw-periods-categorized/' + this.game)
                .then(response => {
                    let drawPeriods = response.data.drawPeriods

                    for (let index in this.fillable) {
                        if (this.fillable[index].field === 'drawPeriodId') {
                            for (let item in drawPeriods) {
                                let drawTime = new Date('1/1/2021 ' + drawPeriods[item].draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                                drawPeriods[item].draw_time = drawTime;
                            }
                            this.fillable[index].options = drawPeriods;
                        }
                    }
                }).catch(err => {
                console.log(err)
            })
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

        getCurrentDate() {
            let date = new Date();
            const month = date.toLocaleString('default', {month: 'numeric'});
            date = date.getFullYear() + "-" + (month < 10 ? "0" + month : month) + "-" + (date.getDate() < 10 ? "0" + date.getDate() : date.getDate());
            return date
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

<style>
.singleBetTable .myTr .v-messages.theme--light {
    display: none;
}
</style>
