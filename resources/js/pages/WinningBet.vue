<template>
    <v-container>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :text="notification.text" :type="notification.type"></Notification>
        </div>
        <div class="row">
            <div class="col-9 ">
                <DataTable
                    :title="title"
                    :contents="contents"
                    :headers="headers"
                    :canAdd="false"
                    :canEdit="false"
                    :canDelete="false"
                />
            </div>

            <div class="col-3">
                <div style="padding: 12px">
                    <h4 class="text-uppercase" style="margin-bottom: 25px; font-weight: 400">Draw Date</h4>
                    <v-date-picker
                        v-model="date2"
                        @change="displayWinningBets"
                        elevation="5"
                        width="100%"
                    ></v-date-picker>
                </div>
            </div>

        </div>
    </v-container>
</template>

<script>
import '@mdi/font/css/materialdesignicons.css'
import Notification from "../components/Notification";
import DataTable from "../components/DataTable";


export default {
    name: "WinningBet",
    components: {
        Notification,
        DataTable
    },
    data: () => ({
        title: "Winning Bet",
        panel: [0, 0],
        readonly: false,

        ex4: ['red', 'indigo', 'orange', 'primary', 'secondary', 'success', 'info', 'warning', 'error', 'red darken-3', 'indigo darken-3', 'orange darken-3'],
        arrayEvents: null,
        date1: new Date().toISOString().substr(0, 10),
        date2: new Date().toISOString().substr(0, 10),
        selectedDate: String,

        headers: [
            {text: "Agent", value: "agent_name"},
            {text: "Transaction Code", value: "transaction_code"},
            {text: "Draw Date", value: "draw_date"},
            {text: "Draw Period", value: "draw_period"},
            {text: "Game", value: "game"},
            {text: "Combinations", value: "combination"},
            {text: "₱ Amount Hits", value: "hits"},
            {text: "₱ Payable Amount", value: "payable_amount"},
            {text: "Claimed", value: "claimed"},
        ],
        contents: [],

        page: 1,
        pageCount: 0,
        itemsPerPage: 100,
        tabs: '',
        rendered: false,
        notifications: [],

        loadingRequest: "okay",

    }),

    mounted() {
        this.arrayEvents = [...Array(6)].map(() => {
            const day = Math.floor(Math.random() * 30)
            const d = new Date()
            d.setDate(day)
            return d.toISOString().substr(0, 10)
        })
        this.displayWinningBets()
    },

    methods: {
        functionEvents(date) {
            const [, , day] = date.split('-')
            if ([12, 17, 28].includes(parseInt(day, 10))) return true
            if ([1, 19, 22].includes(parseInt(day, 10))) return ['red', '#00f']
            return false
        },

        async displayWinningBets() {
            axios.get('/api/v1/games/winners/' + this.date2)
                .then(response => {
                    let data = response.data.winning_bets
                    this.contents = [];
                    for (let i = 0; i < data.length; i++) {
                        this.contents.push({
                            agent_name: data[i].agent,
                            transaction_code: data[i].transaction_code,
                            draw_date: data[i].draw_date,
                            draw_period: data[i].draw_period,
                            game: data[i].game,
                            combination: data[i].combination,
                            hits: data[i].amount,
                            payable_amount: data[i].payable,
                            claimed: data[i].claimed === 1,
                        })
                    }
                }).catch(err => {
                console.log(err)
            })
        }
    },

}
</script>

<style scoped>

</style>
