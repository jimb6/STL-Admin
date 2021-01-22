<template>
    <div>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>
        <div>
            <v-tabs slot="extension"
                    v-model="tabs"
                    slider-color="primary"
                    color="danger"
                    light
            >
                <!-- TAB TITLES -->
                <v-tab>
                    <v-icon small class="mr-2">mdi-settings</v-icon>
                    Config / Monitoring
                </v-tab>
                <v-tab>
                    <v-icon small class="mr-2">mdi-sitemap</v-icon>
                    Entries
                </v-tab>
                <v-tab>
                    <v-icon small class="mr-2">mdi-trophy</v-icon>
                    Winning Combinations
                </v-tab>
                <v-tab>
                    <v-icon small class="mr-2">mdi-file</v-icon>
                    Reports
                </v-tab>

                <!-- TAB ITEMS -->
                <v-tab-item>
                    <Configuration :game="this.game"/>
                </v-tab-item>

                <v-tab-item>
                    <Entries :game="this.game"/>
                </v-tab-item>

                <v-tab-item>
                    <WinningCombination :game="this.game" />
                </v-tab-item>

                <v-tab-item>
                    <Reports :game="this.game"/>
                </v-tab-item>
            </v-tabs>
        </div>
    </div>
</template>

<script>
import DataTable from "../../components/DataTable";
import Card2 from "../../components/Card2";
import Notification from "../../components/Notification";
import Vue from "vue";
import Vuetify from 'vuetify'
import Bet from '../Bet'
import '@mdi/font/css/materialdesignicons.css'
import VueJsonToCsv from 'vue-json-to-csv'
import Configuration from "../../components/games/Configuration";
import BetsReportDatatable from "../../components/BetsReportDatatable";
import GameDraw from "./GameDraw";
import Entries from "../Entries";
import WinningCombination from "../WinningCombination";
import Reports from "../Reports";

Vue.use(Vuetify, VueJsonToCsv)

export default {
    name: "Game",
    props: ['game'],
    components: {
        GameDraw,
        Configuration,
        Reports,
        DataTable,
        Card2,
        Notification,
        Bet,
        WinningCombination,
        BetsReportDatatable,
        Entries
    },
    data: () => ({
        panel: [0, 0],
        readonly: false,

        editedIndex: -1,

        title: "Game",
        defConfigHeaders: [
            {
                text: 'Name',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            {text: 'Value', value: 'config'},
        ],
        defConfigContents: [],

        daysConfigContents: [],

        controlNumberHeaders: [
            {
                text: 'Combi',
                align: 'start',
                sortable: false,
                value: 'combination',
            },
            {text: 'Max Amount', value: 'max_amount'},
            {text: 'Actions', value: 'control_number_actions', sortable: false},
        ],
        controlNumberData: [],

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
            {label: "Prize", field: "prize", value: "", type: "input"},
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
        editedItem: {},
        notifications: [],

        page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        tabs: '',

        total: 1000,
        canAdd: true,
        canEdit: true,
        canDelete: true,
        hasTopHeader: false,

        dialog: false,
        dialogDelete: false,


    }),

    methods: {

        async storeGame(item) {
            const response = await axios.post('/api/v1/games',
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


        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },
    }
}
</script>
