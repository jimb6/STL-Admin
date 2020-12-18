<template>
    <div class="dashboard">
        <div v-if="errors.length>0" v-for="error in errors">
            <error-notif :message="error.message"></error-notif>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <Card v-bind:cards="cards"/>
            </div>

            <div class="col-lg-9">
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
            <div class="col-3">

                <v-card>
                    <v-subheader :inset="inset">Top Games</v-subheader>

                    <v-list>
                        <template v-for="(item, index) in items">
                            <v-list-item
                                v-if="item.action"
                                :key="item.title"
                                @click=""
                            >
                                <v-list-item-action>
                                    <v-icon>{{ item.action }}</v-icon>
                                </v-list-item-action>

                                <v-list-item-content>
                                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>

                            <v-divider
                                v-else-if="item.divider"
                                :key="index"
                            ></v-divider>
                        </template>
                    </v-list>
                </v-card>
            </div>
            <div class="col-12">
                <v-tabs>
                    <v-tab>Table View</v-tab>
                    <v-tab>Card View</v-tab>
                    <v-tab-item>
                        <DataTable
                            :tableName="tableName"
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
                            :tableName="tableName"
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
    props: {
        userData: JSON,
    },
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
            labels: [
                '12am',
                '3am',
                '6am',
                '9am',
                '12pm',
                '3pm',
                '6pm',
                '9pm',
            ],
            value: [
                200,
                675,
                410,
                390,
                310,
                460,
                250,
                240,
            ],
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


            tableName: "Active Agents",
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

            errors: [],
        };
    },

    async created() {
        const data = {
            collections: [
                {date: "Dec 1", total: "300000"},
                {date: "Dec 2", total: "350000"},
                {date: "Dec 3", total: "250000"},
                {date: "Dec 4", total: "370350"},
                {date: "Dec 5", total: "208100"},
                {date: "Dec 6", total: "208900"},
                {date: "Dec 7", total: "304420"},
                {date: "Dec 8", total: "300100"},
                {date: "Dec 9", total: "389300.50"},
                {date: "Dec 10", total: "357010"},
                {date: "Dec 11", total: "300000"},
                {date: "Dec 12", total: "350000"},
                {date: "Dec 13", total: "250000"},
                {date: "Dec 14", total: "370350"},
                {date: "Dec 15", total: "258100"},
                {date: "Dec 16", total: "208900"},
                {date: "Dec 17", total: "304420"},
                {date: "Dec 18", total: "300100"},
                {date: "Dec 19", total: "389300"},
                {date: "Dec 20", total: "357010"},
                {date: "Dec 21", total: "300000"},
                {date: "Dec 22", total: "350000"},
                {date: "Dec 23", total: "250000"},
                {date: "Dec 24", total: "370350"},
                {date: "Dec 25", total: "0"},
                {date: "Dec 26", total: "208900"},
                {date: "Dec 27", total: "304420"},
                {date: "Dec 28", total: "300100"},
                {date: "Dec 29", total: "389300"},
                {date: "Dec 30", total: "357010"},
                {date: "Dec 31", total: "357010"},
            ],
        };
        data["collections"].forEach(d => {
            const date = d.date;
            const {
                total,
            } = d;
            this.arrCollections.push({date, total: total});
            this.totalCollection += parseFloat(total);
        });
        this.totalCollection = this.formatCurrencies(this.totalCollection);
        await this.displayActiveAgents();
    },
    methods: {

        formatCurrencies(money) {
            money = (Math.round(money * 100) / 100).toFixed(2);
            return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },


        async displayActiveAgents() {
            const response = await axios.get('agents/active/all/?').catch(err => {
                this.errors.push({message: "Error authenticating user", error: err})
            });
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
        },

        async updateUser() {

        },

        async destroyAgent(item) {
            const response = await axios.delete('agents/' + item.id).catch(err => this.errors.push({message: "Error authenticating user", error: err}))
            await this.displayAgents();
        },

        async getClusters() {
            const response = await axios.get('clusters').catch(err => this.errors.push({message: "Error authenticating user", error: err}))
            let clustersData = response.data.clusters;
            for (let index in this.fillable) {
                if (this.fillable[index].field == 'cluster') {
                    this.fillable[index].options = clustersData;
                }
            }
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
