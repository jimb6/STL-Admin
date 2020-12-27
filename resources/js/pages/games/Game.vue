<template>
    <v-main>
        <v-container>
            <div v-if="notifications.length > 0" v-for="notification in notifications">
                <Notification :notification="notification"></Notification>
            </div>
            <div class="row">
                <div class="col-12">
                    <v-tabs slot="extension"
                            v-model="tabs"
                            slider-color="primary"
                            color="danger"
                            light>
                        <v-tab>
                            <v-icon small class="mr-2">mdi-settings</v-icon>
                            Config / Monitoring
                        </v-tab>
                        <v-tab>Transaction Logs</v-tab>
                        <v-tab>Winning Combination</v-tab>
                        <v-tab>Reports</v-tab>
                        <v-tab-item>
                            <Configuration :game="this.game"/>
                        </v-tab-item>

                        <v-tab-item>
                            <Bet :game="this.game"></Bet>
                        </v-tab-item>
                        <v-tab-item>
                            <WinningCombination/>
                        </v-tab-item>
                        <v-tab-item>
                            <Reports/>
                        </v-tab-item>
                    </v-tabs>
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Notification from "../components/Notification";
import Vue from "vue";
import Vuetify from 'vuetify'
import Bet from './Bet'
import WinningCombination from "./WinningCombination";
import '@mdi/font/css/materialdesignicons.css'
import Reports from "./Reports";
import VueJsonToCsv from 'vue-json-to-csv'
import Configuration from "../components/games/Configuration";

Vue.use(Vuetify, VueJsonToCsv)

export default {
    name: "Game",
    props: ['game'],
    components: {
        Configuration,
        Reports,
        DataTable,
        Card2,
        Notification,
        Bet,
        WinningCombination
    },
    data: () => ({
        panel: [0, 0],
        readonly: false,

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',
        pagination: {},

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

        canAdd: true,
        canEdit: true,
        canDelete: true,

        dialog: false,
        dialogDelete: false,

    }),

    methods: {

        async storeGame(item) {
            const response = await axios.post('/api/games',
                {
                    'description': item.description,
                    'abbreviation': item.abbreviation,
                    'prize': item.prize,
                    'field_set': item.field_set,
                    'digit_per_field_set': item.digit_per_field_set,
                    'min_number': item.min_number,
                    'max_number': item.max_number,
                    'has_repetition': item.has_repetition,
                    'days_availability': item.days_availability,
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
        }
    }
}
</script>
