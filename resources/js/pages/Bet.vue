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
                        :canAdd="!canAdd"
                        :canEdit="canEdit"
                        :canDelete="!canDelete"

                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :title="title"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        :canAdd="!canAdd"
                        :canEdit="canEdit"
                        :canDelete="!canDelete"
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
    name: "Bet",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
    },

    data: () => ({
        title: "Cluster Bet",
        headers: [
            {text: "#", value: "count"},
            {text: "Combination", value: "combination"},
            {text: "Rumbled", value: "is_rumbled"},
            {text: "Voided", value: "is_voided"},
            {text: "Bet Amount", value: "bet_amount"},
            {text: "Winning Amount", value: "winning_amount"},
            {text: "Net Amount", value: "net_amount"},
            {text: "Close", value: "isClose", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Draw Time", field: "draw_time", value: "", type: "timepicker"},
            {label: "Draw Type", field: "draw_type", value: "", type: "select", options: ["Local", "National"]},
        ],

        editedItem: {},
        address: Array,

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayBets();
        this.listen();
    },
    methods: {
        async displayBets() {
            await axios.get('/api/v1/bet-transactions')
                .then(response => {
                    console.log(response)
                    let bet = {};
                    const data = response.data.bets;
                    let date = '';
                    let count = 0;
                    this.contents = []
                    let sum = 0;
                    // for (let item in data) {
                    //     sum += data[item].amount;
                    // }
                    for (let item in data) {
                        date = this.getDateToday(new Date(data[item].updated_at));
                        count++;
                        bet = {
                            count: count,
                            id: data[item].id,
                            combination: data[item].combination,
                            is_rumbled: data[item].is_rumbled,
                            is_voided: data[item].is_voided,
                            bet_amount: data[item].amount,
                            winning_amount: (data[item].amount * data[item].game.prize),
                            net_amount: sum - (data[item].amount * data[item].game.prize),
                        }
                        this.contents.push(bet);
                    }
                }).catch(err => {
                    console.log(err)
                });
        },

        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        formatMoney(money) {
            money = (Math.round(money * 100) / 100).toFixed(2);
            return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },

        async listen() {
            axios.get('/api/user').then(response => {
                Echo.channel('bet.transaction.' + response.data.user.cluster_id)
                    .listen('NewBetTransactionAdded', (device) => {
                        this.getRegistrationQr();
                        this.displayDevices();
                    });
            }).catch(err => console.log(err))
        }

    }
}
</script>
