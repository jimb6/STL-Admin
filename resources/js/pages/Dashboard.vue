<template>
    <div class="dashboard">
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
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
                            <v-list-item>
                                <v-list-item-action>
                                    {{ game.abbreviation + -+game.max_number }}
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
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'
import Notification from '../components/Notification'
import moment from 'moment'

Vue.use(Vuetify)

export default {
    name: "Dashboard",
    components: {
        Notification,
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


            title: "Active Agents",
            headers: [
                {text: "#", value: "count"},
                {text: "Name", value: "name"},
                // {text: "Birthdate", value: "birthdate"},
                // {text: "Gender", value: "gender"},
                {text: "Contact #", value: "contact_number"},
                {text: "Device", value: "device_code"},
                // {text: "Active", value: "last_active"},
                // {text: "Last Update", value: "updated_at"},
                // {text: "Actions", value: "actions", sortable: false},
            ],
            contents: [],
            fillable: [],

            editedItem: {},
            address: Array,

            canAdd: true,
            canEdit: true,
            canDelete: true,

            canViewActiveAgents: false,
            canViewBets: false,
            canViewTransactions: false,

            notifications: [],
            games: [],
        };
    },

    async created() {
        const data = {};
        this.totalCollection = this.formatCurrencies(this.totalCollection);
        await this.displayActiveAgents();
        await this.getDrawPeriods();
        await this.getBetsPerformance();
        await this.getGamesPerformance();
        await this.listen();
    },
    methods: {

        async getDrawPeriods() {
            await axios.get('/api/v1/draw-periods').then(response => {
                for (let item in response.data.drawPeriods) {
                    this.labels.push(response.data.drawPeriods[item].draw_time)
                    this.value.push(response.data.drawPeriods[item].id)
                }
            }).catch(err => this.addNotification("Error fetching draw periods.", "error", err.status))
        },
        async getGamesPerformance() {
            await axios.get('/api/v1/games').then(response => {
                this.canViewTransactions = true
                this.games = response.data.games
            }).catch(err => this.addNotification("Error fetching games.", "error", err.status))
        },

        async getBetsPerformance() {
            await axios.get('/api/v1/bets', {
                body: {
                    'date': moment(new Date()).format('YYYY-MM-DD'),
                }
            }).then(response => {

                this.canViewBets = true
                console.log(response)
            }).catch(err => this.addNotification("Error fetching draw periods.", "error", err.status))
        },

        async displayActiveAgents() {
            await axios.get('/api/v1/agents/active/all').then(response => {
                console.log(response)
                this.canViewActiveAgents = true
                let agent = {};
                const data = response.data.agents;
                let date = '';
                let count = 0;
                this.contents = []
                this.cards[0].description = data.length
                for (let item in data) {
                    count++;
                    date = this.getDateToday(new Date(data[item].last_activity));
                    agent = {
                        count: count,
                        id: data[item].id,
                        name: data[item].name,
                        contact_number: data[item].contact_number,
                        device_code: data[item].device.device_code,
                        // ip_address: data[item].ip_address,
                        // last_active: date,
                    }
                    this.contents.push(agent);
                }
            }).catch(err => {
                console.log(err)
                this.addNotification("Error fetching active agents.", "error", err.status)
                this.canViewActiveAgents = false
            });

        },

        async getClusters() {
            await axios.get('clusters')
                .then(response => {
                    for (let index in this.fillable) {
                        if (this.fillable[index].field == 'cluster') {
                            this.fillable[index].options = clustersData;
                        }
                    }
                })
                .catch(err => this.addNotification("Error fetching clusters.", "error", err.status))
            let clustersData = response.data.clusters;

        },

        async listen() {
            Echo.channel('active.agent')
                .listen('NewActiveAgent', (device) => {
                    this.displayActiveAgents();
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


        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        }
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
