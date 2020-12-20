<template>
    <div class="dashboard">
        <div v-if="errors.length>0" v-for="error in errors">
            <error-notif :message="error.message"></error-notif>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <Card v-bind:cards="cards"/>
            </div>

            <div class="col-lg-9" v-show="canViewBets">
                <v-card
                    class="mt-4 mx-auto"
                    max-width="100%">
                    <v-sheet
                        class="v-sheet--offset mx-auto"
                        color="#1D3557"
                        elevation="12"

                        max-width="calc(100% - 32px)">
                        <v-sparkline
                            :labels="labels"
                            :value="value"
                            color="white"
                            line-width="2"
                            padding="16"
                        ></v-sparkline>
                    </v-sheet>

                    <v-card-text class="pt-0">
                        <div class="title font-weight-light mb-2">Today's Bets Performance</div>
                        <!--                        <div class="subheading font-weight-light grey&#45;&#45;text">Last Campaign Performance</div>-->
                        <v-divider class="my-2"></v-divider>
                        <v-icon
                            class="mr-2"
                            small>
                            mdi-clock
                        </v-icon>
                        <span class="caption grey--text font-weight-light">last bet transactions 24 hours ago</span>
                    </v-card-text>
                </v-card>
            </div>
            <div class="col-3" v-show="canViewTransactions">
                <v-card>
                    <v-subheader :inset="inset">Top Games</v-subheader>
                    <v-list>
                        <template v-for="(game, index) in games">
                            <v-list-item >
                                <v-list-item-action>
                                    {{ game.abbreviation + - + game.max_number}}
                                </v-list-item-action>
                            </v-list-item>
                        </template>
                    </v-list>
                </v-card>
            </div>
            <div class="col-12" v-show="canViewActiveAgents">
                <v-tabs>
                    <v-tab>Table View</v-tab>
                    <v-tab>Card View</v-tab>
                    <v-tab-item>
                        <DataTable
                            :title="title"
                            :contents="contents"
                            :headers="headers"
                            :fillable="fillable"
                            @storeUser="storeAgent($event)"
                            @changeAddress="changeAddress($event)"
                            @destroyUser="destroyAgent($event)"
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
                            @storeUser="storeAgent($event)"
                            @changeAddress="changeAddress($event)"
                            @destroyUser="destroyAgent($event)"
                            :canAdd="canAdd"
                            :canEdit="canEdit"
                            :canDelete="canDelete"
                        />
                    </v-tab-item>
                </v-tabs>
            </div>
        </div>
    </div>

</template>

<script>
import Card from "../components/Card";
import LineChart from "../components/LineChart";
import NotificationCard from "../components/NotificationCard";
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Dashboard",
    components: {
        NotificationCard,
        Card,
        LineChart,
        Card2,
        DataTable,
    },
    data() {
        return {
            cards: [
                {
                    id: 1,
                    icon: "fas fa-users",
                    title: "Active Agents",
                    description: "",
                },
                {
                    id: 2,
                    icon: "fas fa-store",
                    title: "Gross Income",
                    description: "",
                },
                {
                    id: 3,
                    icon: "fas fa-trophy",
                    title: "Transactions",
                    description: "",
                },
            ],
            labels: [],
            value: [],
            inset: false,
            items: [
                {
                    action: 'STL 2D',
                    title: '12,241.00',
                },
                {
                    divider: true,
                },
                {
                    action: 'send',
                    title: 'send',
                },
                {
                    divider: true,
                },
                {
                    action: 'delete',
                    title: 'trash',
                },
            ],
            arrCollections: [],
            collectionsChartColors: {
                borderColor: "#1d3557",
                pointBorderColor: "#1d3557",
                pointBackgroundColor: "#f1faee",
                backgroundColor: "#a8dadc"
            },
            totalCollection: 0,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.yLabel;
                        }
                    }
                }
            },
            activeAgents: 0,
            activeBooths: 0,


            title: "Active Agents",
            headers: [
                {text: "#", value: "count"},
                {text: "Name", value: "name"},
                // {text: "Birthdate", value: "birthdate"},
                // {text: "Gender", value: "gender"},
                {text: "Contact #", value: "contact_number"},
                {text: "IP", value: "ip_address"},
                {text: "Active", value: "last_active"},
                // {text: "Last Update", value: "updated_at"},
                {text: "Actions", value: "actions", sortable: false},
            ],
            contents: [],
            fillable: [
                {label: "Name", field: "name", value: "", type: "input"},
                // {label: "Birthdate", field: "birthdate", value: "", type: "datepicker"},
                // {label: "Gender", field: "gender", value: "", type: "select", options: ["Male", "Female", "Others"]},
                {label: "Contact #", field: "contact_number", value: "", type: "input"},
                {label: "IP", field: "ip_address", value: "", type: "input"},
                {label: "Active", field: "last_active", value: "", type: "input"},
                // {label: "Cluster", field: "cluster", value: "", type: "select", options: Array},
            ],

            editedItem: {},
            address: Array,

            canAdd: true,
            canEdit: true,
            canDelete: true,

            canViewActiveAgents: false,
            canViewBets: false,
            canViewTransactions: false,

            errors: [],
            games: [],
        };
    },

    async created() {
        const data = {};
        this.totalCollection = this.formatCurrencies(this.totalCollection);
        await this.displayActiveAgents();
        await this.getDrawPeriods();
        await this.getBets();
        await this.getBetsPerformance();
        await this.getGamesPerformance();
        await this.listen();
    },
    methods: {

        async getDrawPeriods(){
            await axios.get('/api/v1/draw-periods').then(response => {
                for (let item in response.data.drawPeriods){
                    console.log(response.data.drawPeriods[item].draw_time)
                    this.labels.push(response.data.drawPeriods[item].draw_time)
                    this.value.push(response.data.drawPeriods[item].id)
                }
            }).catch(err => console.log(err))
        },

        async getBets(){
            await axios.get('/api/v1/bets').then(response => {
                console.log(response)
            }).catch(err => console.log(err))
        },

        async getGamesPerformance() {
            await axios.get('/api/v1/games').then(response => {
                this.canViewTransactions = true
                this.games = response.data.games
            }).catch(err => this.errors.push({
                message: "Error authenticating user",
                error: err
            }))
        },

        async getBetsPerformance() {
            await axios.get('/api/v1/bets').then(response => {
                this.canViewBets = true
            }).catch(err => console.log(err))
        },

        async displayActiveAgents() {
            await axios.get('/api/v1/agents/active/').then(response => {
                this.canViewActiveAgents = true
                let agent = {};
                const data = response.data.agents;
                let date = '';
                let count = 0;
                this.contents = []
                for (let item in data) {
                    if (data[item].user == null)
                        continue
                    count++;
                    date = this.getDateToday(new Date(data[item].last_activity));
                    agent = {
                        count: count,
                        id: data[item].user.id,
                        name: data[item].user.name,
                        contact_number: data[item].user.contact_number,
                        ip_address: data[item].ip_address,
                        last_active: date,
                    }
                    this.contents.push(agent);
                }
            }).catch(err => {
                this.errors.push({message: "Error authenticating user", error: err})
                this.canViewActiveAgents = false
            });

        },

        async getClusters() {
            const response = await axios.get('clusters').catch(err => this.errors.push({
                message: "Error authenticating user",
                error: err
            }))
            let clustersData = response.data.clusters;
            for (let index in this.fillable) {
                if (this.fillable[index].field == 'cluster') {
                    this.fillable[index].options = clustersData;
                }
            }
        },

        async listen() {
            Echo.private('device-store')
                .listen('NewDeviceAdded', (data) => {
                   console.log(data)
                });
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

        formatCurrencies(money) {
            money = (Math.round(money * 100) / 100).toFixed(2);
            return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    }
};
</script>

<style>
.v-sheet--offset {
    top: -24px;
    position: relative;
}

.dashboard .card {
    background: unset;
    box-shadow: unset;
}

.dashboard .carditem-container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    grid-gap: 40px;
    padding: 20px 0;
}

.dashboard .card-item {
    position: relative;
    padding: 20px;
    background: #fff;
    display: flex;
    border-radius: 5px;
    box-shadow: 0 0 5px 1px rgba(0, 0, 0, .15);
}

.dashboard .card-item > div:first-child {
    width: 40%;
}

.dashboard .card-item > div:last-child {
    width: 60%;
}

.dashboard .card-item i {
    position: absolute;
    width: 30%;
    height: 100px;
    top: -20px;
    text-align: center;
    line-height: 100px;
    font-size: 35px;
    color: #fff;
    border-radius: 5px;
    background: linear-gradient(200DEG, rgb(75, 108, 183), rgb(24, 40, 72));
}

.dashboard .card-item h3 {
    text-transform: uppercase;
    font-size: 18px;
    font-weight: 300;
    letter-spacing: 1px;
    text-align: right;
    letter-spacing: 1px;
}

.dashboard .card-item p {
    font-size: 40px;
    text-align: right;
}

.dashboard .cstm-linechart {
    padding: 40px 20px;
    background: #fff;
    border-radius: 5px;
    box-shadow: 0 0 5px 1px rgba(0, 0, 0, .15);
}

.linechart-title {
    position: relative;
    padding-bottom: 20px;
    margin-bottom: 30px;
    border-bottom: 1px solid #f3f3f3;
}

.linechart-title h3 {
    text-transform: uppercase;
    font-weight: 300;
    letter-spacing: 1px;
    margin: 0;
}

.linechart-title p {
    padding: 10px 20px;
    background: #a8dadc;
    color: #fff;
    border-radius: 5px;
    font-weight: 600;
}

.linechart-title p:before {
    content: "₱";
    margin-right: 5px;
}

.card-item-description {
    font-size: 14px;
    color: #555555;
}

</style>
