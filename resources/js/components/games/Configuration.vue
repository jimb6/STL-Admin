<template>
    <v-container>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
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
                    <h2 class="cstm-drawPeriod">{{ this.getFormattedTime(this.drawPeriodConfig.draw_time) }}</h2>
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
    </v-container>
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
        async index() {
            await axios.get(`/api/v1/games/config/${this.game}`)
                .then(response => {
                    this.gameConfig = response.data.game[0];
                    this.drawPeriodConfig = response.data.draw_period
                    const game = this.gameConfig
                    const bets = response.data.bets;
                    this.contents = [];
                    this.controlNumberData = [];
                    this.daysConfigContents = [];
                    this.closedNumbers = response.data.closed_numbers
                    // //For Control Numbers of Game
                    for (let controlItem in game.control_combination) {
                        let contComb = {
                            id: game.control_combination[controlItem].id,
                            combination: game.control_combination[controlItem].combination,
                            max_amount: game.control_combination[controlItem].max_amount,
                        }
                        this.controlNumberData.push(contComb)
                    }
                    // //For Default Configuration of the Game
                    this.daysConfigContents = game.game_configuration.days_availability ? game.game_configuration.days_availability : ["NO DAYS AVAILABLE"]

                    this.defConfigContents = [
                        {col_name: "min_per_bet", name: "MIN BET AMOUNT", config: game.game_configuration.min_per_bet},
                        {col_name: "multiplier", name: "MULTIPLIER", config: game.game_configuration.multiplier},
                        {
                            col_name: "transaction_limit",
                            name: "SEC NOT DUPLICATE",
                            config: game.game_configuration.transaction_limit
                        },
                        {
                            col_name: "max_per_bet",
                            name: "MAX AMOUNT PER BET",
                            config: game.game_configuration.max_per_bet
                        },
                        {col_name: "max_sum_bet", name: "MAX HARD BET", config: game.game_configuration.max_sum_bet}
                    ];

                    this.displayBets(bets)
                })

                .catch(err => {
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        displayBets(bets) {
            this.contents = [];
            this.total = 0;
            for (let item in bets) {
                let isControlled = false
                let isClosed = false;
                let maxAmount = 0
                for (let closeItem in this.closedNumbers) {
                    if (this.closedNumbers[closeItem].combination == item) {
                        isClosed = true
                        break;
                    }
                }
                for (let controlItem in this.gameConfig.control_combination) {
                    if (this.gameConfig.control_combination[controlItem].combination == item) {
                        isControlled = true
                        maxAmount = this.gameConfig.control_combination[controlItem].max_amount
                        break;
                    }
                }
                let bet = {
                    combinations: item,
                    amount: bets[item].sum,
                    status: isClosed ? 'CLOSED' : bets[item].sum >= this.gameConfig.game_configuration.max_sum_bet ||
                    (isControlled && bets[item].sum >= maxAmount) ? "SOLD OUT" : "OPEN",
                }
                this.total += bets[item].sum
                this.contents.push(bet)
            }
        },


        async storeCloseCombination(item) {

            await axios.post('/api/v1/close-combination/' + this.gameConfig.id + '/' + this.drawPeriodConfig.id,
                {'combinations': item.combinations})
                .then(response => {
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = true;
                        this.contents[item.index].status = "CLOSED";
                    });
                })
                .catch(err => {
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
                })
                .catch(err => {
                    this.$nextTick(function () {
                        this.contents[item.index].isClosed = !item.isClosed;
                    });
                })

        },

        async storeGame(item) {
            const response = await axios.post('/api/games',
                {
                    'description': item.description,
                    'abbreviation': item.abbreviation,
                    'multiplier': item.multiplier,
                    'field_set': item.field_set,
                    'digit_per_field_set': item.digit_per_field_set,
                    'min_per_bet': item.min_per_bet,
                    'max_per_bet': item.max_per_bet,
                    'has_repetition': item.has_repetition,
                    'days_availability': item.days_availability,
                    'is_rumbled': item.is_rumbled,
                    'max_sum_bet': item.max_sum_bet,
                    'transaction_limit': item.transaction_limit,
                    'min_per_field_set': item.min_per_field_set,
                    'max_per_field_set': item.max_per_field_set,
                })
                .then(response => {
                    this.addNotification(item.description + " added successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.description + " unsuccessfully added!", "error", "400");
                });

            await this.displayGames()
        },

        async updateGame() {

        },

        async destroyGame(item) {
            const response = await axios.delete('/api/v1/games/' + item.id)
                .then(response => {
                    this.addNotification(item.description + " deleted successfully!", "success", "200")
                })
                .catch(err => {
                    this.addNotification(item.description + " unsuccessfully deleted!", "error", "400")
                });
            await this.displayGames();
        },

        // DEFAULT CONFIGURATIONS
        async updateDefaultConfig(item) {
            // this.addNotification(item.col_name + " <> " + item.name + " <> " + item.config, "success", 200);
            axios.put('/api/v1/games/config/default/' + this.game, item).then(response => {
                this.addNotification("Configuration Changed!", "success", 200);
            }).catch(err => {
                this.addNotification("Error Occurred !", "error", err.status);
            })
        },

        // DAYS AVAILABILITY
        async updateDaysAvailability(item) {
            axios.put('/api/v1/games/config/days/' + this.game, {
                'days': item
            }).then(response => {
                this.addNotification("updateControlCombination", "success", 200);
            }).catch(err => {

            })
        },

        // CONTROL COMBINATIONS
        async updateControlCombination(item) {
            axios.put('/api/v1/games/control-combination/' + this.game, {
                'id': item.id,
                'combination': item.combination,
                'max_amount': item.max_amount
            }).then(response => {
                this.addNotification("updateControlCombination", "success", 200);
            }).catch(err => {

            })
        },
        async storeControlCombination(item) {
            axios.post('/api/v1/games/control-combination/' + this.game, {
                'combination': item.combination,
                'max_amount': item.max_amount
            }).then(response => {
                this.addNotification("storeControlCombination", "success", 200);
            }).catch(err => {
            })
        },
        async destroyControlCombination(item) {
            axios.delete('/api/v1/games/control-combination/' + item.id).then(response => {

                this.addNotification("destroyControlCombination", "success", 200);
            }).catch(err => {
                this.addNotification("Error Occurred !", "error", err.status);
            })
        },

        // NOTIFICATION
        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },

        getFormattedTime(time) {
            if (time) {
                time = time.split(":");
                let x = time[0] > 12 ? "PM" : "AM";
                return time[0] % 12 + "" + (time[1] == 0 ? "" : time[1]) + " " + x;
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
                    this.getUpdatedBets();
                    console.log("NEW BET EVENT")
                })
            Echo.channel('controlled.combination.' + this.game)
                .listen('NewControlledBetAdded', (data) => {
                    this.index()
                    console.log("NEW CONTROLLED BET EVENT")
                })

            Echo.channel('default.config.' + this.game)
                .listen('GameConfigEvent', (data) => {
                    this.index()
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
</style>
