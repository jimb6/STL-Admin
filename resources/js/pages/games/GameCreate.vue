<template>
    <v-main>
        <v-container>
            <div v-if="notifications.length > 0" v-for="notification in notifications">
                <Notification :notification="notification"></Notification>
            </div>
            <v-tabs>
                <v-tab>Table View</v-tab>
                <v-tab>Card View</v-tab>
                <v-tab-item>
                    <DataTable
                        :title="title"
                        :headers="headers"
                        :contents="contents"
                        :fillable="fillable"
                        @storeModel="storeGame($event)"
                        @updateModel="updateGame($event)"
                        @destroyModel="destroyGame($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"
                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :title="title"
                        :headers="headers"
                        :contents="contents"
                        :fillable="fillable"
                        @storeModel="storeGame($event)"
                        @updateModel="updateGame($event)"
                        @destroyModel="destroyGame($event)"
                        :canAdd="canAdd"
                        :canEdit="canEdit"
                        :canDelete="canDelete"
                    />
                </v-tab-item>
            </v-tabs>
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../../components/DataTable";
import Card2 from "../../components/Card2";
import Notification from "../../components/Notification";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "GameCreate",
    components: {
        DataTable,
        Card2,
        Notification,
    },
    data: () => ({
        title: "Game",
        headers: [
            {text: "#", value: "count"},
            {text: "Description", value: "description"},
            {text: "Abbreviation", value: "abbreviation"},
            {text: "Multiplier", value: "multiplier"},
            {text: "Days Availability", value: "days_availability"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Description", field: "description", value: "", type: "input"},
            {label: "Abbreviation", field: "abbreviation", value: "", type: "input"},
            {label: "Multiplier", field: "multiplier", value: "", type: "input"},
            {label: "Field Set", field: "field_set", value: "", type: "input"},
            {label: "Digit Per Field Set", field: "digit_per_field_set", value: "", type: "input"},
            {label: "Min Per Field Set", field: "min_per_field_set", value: "", type: "input"},
            {label: "Max Per Field Set", field: "max_per_field_set", value: "", type: "input"},
            {label: "Min Bet", field: "min_per_bet", value: "", type: "input"},
            {label: "Max Bet", field: "max_per_bet", value: "", type: "input"},
            {label: "Max Hard Bet", field: "max_sum_bet", value: "", type: "input"},
            {label: "Has Repetition", field: "has_repetition", value: "", type: "select", options: [true, false]},
            {
                label: "Days Availability",
                field: "days_availability",
                value: "",
                type: "chips",
                options: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
            {label: "Transaction Limit", field: "transaction_limit", value: "", type: "input"},
            {label: "Is Rumbled", field: "is_rumbled", value: "", type: "select", options: [true, false]},
        ],
        editedItem: {},
        notifications: [],

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayGames();
    },
    methods: {
        async displayGames() {
            await axios.get('/api/v1/games')
                .then(response => {
                    let game = {};
                    const data = response.data.games;
                    let count = 0;
                    let date = '';
                    this.contents = [];
                    for (let item in data) {
                        date = this.getDateToday(new Date(data[item].updated_at));
                        count++;
                        game = {
                            count: count,
                            id: data[item].id,
                            description: data[item].description,
                            abbreviation: data[item].abbreviation,
                            multiplier: data[item].game_configuration.multiplier,
                            field_set: data[item].game_configuration.field_set,
                            digit_per_field_set: data[item].game_configuration.digit_per_field_set,
                            min_per_bet: data[item].game_configuration.min_per_bet,
                            max_per_bet: data[item].game_configuration.max_per_bet,
                            max_sum_bet: data[item].game_configuration.max_sum_bet,
                            has_repetition: data[item].game_configuration.has_repetition,
                            days_availability: data[item].game_configuration.days_availability,
                            is_rumbled: data[item].game_configuration.is_rumbled,
                            transaction_limit: data[item].game_configuration.transaction_limit,
                            min_per_field_set: data[item].game_configuration.min_per_field_set,
                            max_per_field_set: data[item].game_configuration.max_per_field_set,
                            updated_at: date,
                        }
                        this.contents.push(game);
                    }
                })
                .catch(err => {
                    console.log(err, 'error')
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        async storeGame(item) {
            await axios.post('/api/v1/games',
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
                    console.log(response);
                    this.addNotification(item.description + " added successfully!", "success", "200");
                })
                .catch(err => {
                    console.log(err);
                    this.addNotification(item.description + " unsuccessfully added!", "error", "400");
                });

            await this.displayGames()
        },

        async updateGame(item) {
            await axios.put('/api/v1/games/'+item.id,
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
                    this.addNotification(item.description + " updated successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.description + " unsuccessfully updated!", "error", "400");
                });
            await this.displayGames();
        },

        async destroyGame(item) {
            const response = await axios.delete('/api/v1/games/' + item.id)
                .then(response => {
                    console.log(response)
                    this.addNotification(item.description + " deleted successfully!", "success", "200")
                })
                .catch(err => {
                    console.log(response)
                    this.addNotification(item.description + " unsuccessfully deleted!", "error", "400")
                });
            await this.displayGames();
        },

        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        }


    }
}
</script>
