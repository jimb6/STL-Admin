<template>
    <v-main>
        <v-container>
            <BetsDatatable
                :title="title"
                :contents="contents"
                :headers="headers"
                :fillable="fillable"
                @displayModel="displayBets($event)"
                :canAdd="canAdd"
                :canEdit="canEdit"
                :canDelete="canDelete"
            />
        </v-container>
    </v-main>
</template>

<script>
import BetsDatatable from "../components/BetsDatatable";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Bet",
    props: ['betType'],
    components: {
        BetsDatatable,
    },

    data: () => ({
        title: "Cluster Bet",
        headers: [
            {text: "#", value: "count"},
            {text: "Combination", value: "combination"},
            {text: "Voided", value: "is_voided"},
            {text: "₱ Bet Amount", value: "bet_amount"},
            {text: "₱ Winning Amount", value: "winning_amount"},
            {text: "₱ Net Amount", value: "net_amount"},
            {text: "Close", value: "isClose", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Draw Time", field: "draw_time", value: "", type: "timepicker"},
            {label: "Draw Type", field: "draw_type", value: "", type: "select", options: ["Local", "National"]},
        ],

        editedItem: {},
        address: Array,
        date: '',

        canAdd: false,
        canEdit: true,
        canDelete: false,
    }),
    created() {
        this.getISODateToday()
        this.displayBets(this.date);
        this.listen();
    },
    methods: {
        async displayBets(date) {
            console.log(date, "<<<<");
            await axios.get('/api/v1/bets-range/'+date)
                .then(response => {
                    let bet = {};
                    const data = response.data.bets;
                    let date = '';
                    let count = 0;
                    this.contents = []
                    let sum = 0;
                    for (let item in data) {
                        sum += data[item].sum;
                    }
                    for (let item in data) {
                        count++;
                        bet = {
                            count: count,
                            combination: item,
                            is_voided: data[item].bets[0].is_voided,
                            bet_amount: (data[item].sum).toLocaleString('en'),
                            winning_amount: (data[item].sum * data[item].bets[0]['game'].prize).toLocaleString('en'),
                            net_amount: (sum - (data[item].sum * data[item].bets[0]['game'].prize)).toLocaleString('en'),
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

        getISODateToday() {
            let date = new Date();
            const month = date.toLocaleString('default', {month: 'numeric'});
            date = date.getFullYear() + "-" + month + "-" + date.getDate();
            this.maxDate = date;
            this.date = date;
        },

        async listen() {
            Echo.channel('bet.transaction')
                .listen('NewBetTransactionAdded', (bets) => {
                    console.log(bets)
                    this.displayBets();
                });
        }

    }
}
</script>
