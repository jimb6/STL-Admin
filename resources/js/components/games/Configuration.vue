<template>
    <div>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :text="notification.text" :type="notification.type"></Notification>
        </div>
        <div class="row">
            <div class="col-9 ">
                <BetsDatatable
                    :title="game"
                    :total="total"
                    :headers="headers"
                    :contents="contents"
                    :fillable="fillable"
                    @displayModel="displayBets($event)"
                    @storeCloseCombination="storeCloseCombination($event)"
                    @destroyCloseCombination="destroyCloseCombination($event)"
                    :canAdd="canAdd"
                    :canEdit="canEdit"
                    :canDelete="canDelete"
                    :hasTopHeader="hasTopHeader"
                    class="singleBetTable"
                    :loadingRequest="loadingRequest"
                />

            </div>

            <div class="col-3">
                <div>
                    <h3 class="cstm-date">{{ this.getDateToday() }}</h3>
                    <h2 class="cstm-drawPeriod">{{
                            this.drawPeriodConfig === null ? "In Progress " : this.getFormattedTime(this.drawPeriodConfig.draw_time)
                        }}</h2>
                    <v-expansion-panels
                        v-model="panel"
                        :readonly="readonly"
                        class="pt-5"
                        multiple
                        accordion
                    >

                        <!-- DEFAULT CONFIGURATION -->
                        <DefaultConfiguration
                            :game="game"
                            :defConfigHeaders="defConfigHeaders"
                            :defConfigContents="defConfigContents"
                            @updateDefaultConfig="updateDefaultConfig($event)"
                        />

                        <DaysAvailability
                            :game="game"
                            :daysConfigContents="daysConfigContents"
                            @updateDaysAvailability="updateDaysAvailability($event)"
                        />

                        <!-- CONTROL COMBINATIONS -->
                        <ControlCombinations
                            :game="game"
                            :controlNumberHeaders="controlNumberHeaders"
                            :controlNumberData="controlNumberData"
                            @updateControlCombination="updateControlCombination($event)"
                            @storeControlCombination="storeControlCombination($event)"
                            @destroyControlCombination="destroyControlCombination($event)"

                        />

                    </v-expansion-panels>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import DefaultConfiguration from "./DefaultConfiguration";
import DaysAvailability from "./DaysAvailability";
import ControlCombinations from "./ControlCombinations";
import Notification from "../Notification";
import BetsDatatable from "../../components/BetsDatatable";

export default {
    name: "Configuration",
    props: ['game'],
    components: {
        DefaultConfiguration,
        DaysAvailability,
        ControlCombinations,
        Notification,
        BetsDatatable
    },
    data: () => ({
        panel: [0, 0],
        readonly: false,
        daysConfigContents: [],


        headers: [
            {text: "Combinations", value: "combinations"},
            {text: "₱ Total Amount", value: "amount"},
            {text: "Status", value: "status"},
            {text: "Actions", value: "isClosed"},
        ],
        contents: [],
        fillable: [
            {label: "Description", field: "description", value: "", type: "input"},
            {label: "Abbreviation", field: "abbreviation", value: "", type: "input"},
            {label: "Prize", field: "multiplier", value: "", type: "input"},
            {label: "Field Set", field: "field_set", value: "", type: "input"},
            {label: "Digit Per Field Set", field: "digit_per_field_set", value: "", type: "input"},
            {label: "Min Per Field Set", field: "min_per_field_set", value: "", type: "input"},
            {label: "Max Per Field Set", field: "max_per_field_set", value: "", type: "input"},
            {label: "Min Number", field: "min_number", value: "", type: "input"},
            {label: "Max Number", field: "max_number", value: "", type: "input"},
            {label: "Has Repetition", field: "has_repetition", value: "", type: "select", options: [true, false]},
            {
                label: "Days Availability",
                field: "days_availability",
                value: "",
                type: "chips",
                options: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
        ],

        page: 1,
        pageCount: 0,
        itemsPerPage: 100,
        tabs: '',
        rendered: false,

        savingDefaultConfig: false,
        savingControledNumber: false,
        savingDaysConfig: false,

        // Bets
        total: 0,
        canAdd: false,
        canEdit: true,
        canDelete: false,
        hasTopHeader: false,

        // Default Configurations
        defConfigHeaders: [
            {text: 'Name', align: 'start', sortable: false, value: 'name',},
            {text: 'Value', value: 'config'},
        ],
        defConfigContents: [],

        // Control Combinations Variables
        controlNumberHeaders: [
            {text: 'Combi', align: 'start', sortable: false, value: 'combination',},
            {text: 'Max Amount', value: 'max_amount'},
            {text: 'Actions', value: 'control_number_actions', sortable: false},
        ],
        controlNumberData: [],
        editedItem: {},
        editedIndex: -1,

        // Notification
        notifications: [],
        gameConfig: Object,
        drawPeriodConfig: Object,
        closedNumbers: Object,

        loadingRequest: "okay",

    }),
    created() {
        this.index();
        this.listen();
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },

    methods: {
        index() {
            this.displayBets()
            this.displayControlledCombinations()
            this.displayGameConfigurations()
        },

        async displayBets() {
            await axios.get('/api/v1/bet-transactions-realtime/' + this.game)
                .then(response => {
                    console.log(response)
                    console.log(response.data, '<<<<')
                    let data = response.data.bets
                    this.drawPeriodConfig = response.data.draw
                    this.contents = [];
                    for (let i = 0; i < data.length; i++) {
                        let status = (data[i].control && data[i].total >= data[i].control) || data[i].total >= data[i].max ? "SOLD OUT" : "OPEN"
                        status = data[i].closed === 1 ? "CLOSED" : status

                        let bet = {
                            combinations: data[i].combination,
                            amount: parseFloat(data[i].total),
                            status: status,
                        }
                        this.contents.push(bet)
                    }
                }).catch(err => {
                    this.addNotification("Error fetching realtime bets.", "error")
                })
        },

        async displayControlledCombinations() {
            await axios.get('/api/v1/games/control-combination/' + this.game)
                .then(response => {
                    console.log(response)
                    let data = response.data
                    this.controlNumberData = []
                    for (let i = 0; i < data.length; i++) {
                        let contComb = {
                            id: data[i].id,
                            combination: data[i].combination,
                            max_amount: data[i].max_amount,
                        }
                        this.controlNumberData.push(contComb)
                    }
                }).catch(err => {
                    this.addNotification("Error fetching realtime bets.", "error")
                })
        },

        async displayGameConfigurations() {
            await axios.get('/api/v1/games/config/' + this.game)
                .then(response => {
                    console.log(response)
                    let data = response.data
                    // //For Default Configuration of the Game
                    this.daysConfigContents = data.days_availability ? data.days_availability : ["NO AVAILABLE DAYS"]
                    this.defConfigContents = [
                        {col_name: "min_per_bet", name: "MIN BET AMOUNT", config: data.min_per_bet},
                        {col_name: "multiplier", name: "MULTIPLIER", config: data.multiplier},
                        {
                            col_name: "transaction_limit",
                            name: "SEC NOT DUPLICATE",
                            config: data.transaction_limit
                        },
                        {
                            col_name: "max_per_bet",
                            name: "MAX AMOUNT PER BET",
                            config:data.max_per_bet
                        },
                        {col_name: "max_sum_bet", name: "MAX HARD BET", config: data.max_sum_bet}
                    ];
                }).catch(err => {
                    console.log(err)
                })
        },

        async storeCloseCombination(item) {

            await axios.post('/api/v1/close-combination/' + this.gameConfig.id + '/' + this.drawPeriodConfig.id,
                {'combinations': item.combinations})
                .then(response => {
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = true;
                        this.contents[item.index].status = "CLOSED";
                    });
                    this.addNotification("Combination closed.", "success")
                })
                .catch(err => {
                    this.addNotification("Error closing combination.", "error")
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = !item.isClosed;
                    });
                })

        },

        async destroyCloseCombination(item) {
            await axios.patch('/api/v1/close-combination/' + this.gameConfig.id + '/' + this.drawPeriodConfig.id, {
                'combinations': item.combinations
            })
                .then(response => {
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = false;
                        this.contents[item.index].status = "OPEN";
                    });
                    this.addNotification("Combination opened.", "success")
                })
                .catch(err => {
                    this.addNotification("Error opening combination.", "error")
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = !item.isClosed;
                    });
                })

        },

        // DEFAULT CONFIGURATIONS
        async updateDefaultConfig(item) {
            // this.addNotification(item.col_name + " <> " + item.name + " <> " + item.config, "success", 200);
            axios.put('/api/v1/games/config/default/' + this.game, item).then(response => {
                this.addNotification("Configuration updated.", "success")
            }).catch(err => {
                this.addNotification("Error updating configuration!", "error");
            })
        },

        // CONTROL COMBINATIONS
        async updateControlCombination(item) {
            axios.put('/api/v1/games/control-combination/' + this.game, {
                'id': item.id,
                'combination': item.combination,
                'max_amount': item.max_amount
            }).then(response => {
                this.addNotification("Combination updated.", "success");
            }).catch(err => {
                this.addNotification("Error updating combination", "error");
            })
        },
        async storeControlCombination(item) {
            axios.post('/api/v1/games/control-combination/' + this.game, {
                'combination': item.combination,
                'max_amount': item.max_amount
            }).then(response => {
                this.addNotification("Combination stored.", "success");
            }).catch(err => {
                this.addNotification("Error storing combination.", "error");
            })
        },
        async destroyControlCombination(item) {
            axios.delete('/api/v1/games/control-combination/' + item.id).then(response => {

                this.addNotification("Error storing combination.", "success");
            }).catch(err => {
                this.addNotification("Error deleting combination", "error", err.status);
            })
        },

        // NOTIFICATION
        addNotification(message, type) {
            this.notifications.push({text: message, type: type});
        },

        getFormattedTime(time) {
            if (time) {
                time = time.split(":");
                let x = time[0] > 12 ? "PM" : "AM";
                return time[0] % 12 + "" + (time[1] == 0 ? "" : ":" + time[1]) + " " + x;
            }

        },

        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear();
            return date;
        },

        async getUpdatedBets() {
            await axios.get('/api/v1/bets/' + this.gameConfig.id + '/' + this.drawPeriodConfig.id)
                .then(response => {
                    this.closedNumbers = response.data.closed_numbers
                    this.displayBets(response.data.bets)
                })
                .catch(err => {
                    this.addNotification("Error Occurred !", "error", err.status);
                })
        },


        async listen() {
            Echo.channel('bets.' + this.game)
                .listen('NewBetTransactionAdded', (data) => {
                    this.displayBets();
                    console.log("NEW BET EVENT")
                })
            Echo.channel('controlled.combination.' + this.game)
                .listen('NewControlledBetAdded', (data) => {
                    this.displayControlledCombinations()
                    this.displayBets()
                    console.log("NEW CONTROLLED BET EVENT")
                })

            Echo.channel('default.config.' + this.game)
                .listen('GameConfigEvent', (data) => {
                    this.displayGameConfigurations()
                    this.displayBets()
                    console.log("GAME CONFIG EVENT")
                })
        }
    }
}
</script>

<style scoped>
h3.cstm-date {
    text-align: center;
    text-transform: uppercase;
    font-size: 22px;
    font-weight: 300;
    letter-spacing: 1px;
}

h2.cstm-drawPeriod {
    text-align: center;
    text-transform: uppercase;
    font-weight: 600;
    padding-bottom: unset;
}
.v-expansion-panels {
    width: calc(100% - 10px);
}
</style>
