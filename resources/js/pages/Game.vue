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
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Notification from "../components/Notification";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Game",
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
            {text: "Prize", value: "prize"},
            {text: "Days Availability", value: "days_availability"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
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

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayGames();
    },
    methods: {
        async displayGames() {
            const response = await axios.get('/api/v1/games')
                .then(response => {
                    let game = {};
                    const data = response.data.games;
                    let count = 0;
                    let date = '';
                    this.contents = []
                    for (let item in data) {
                        date = this.getDateToday(new Date(data[item].updated_at));
                        count++;
                        game = {
                            count: count,
                            id: data[item].id,
                            description: data[item].description,
                            abbreviation: data[item].abbreviation,
                            prize: data[item].prize,
                            field_set: data[item].field_set,
                            digit_per_field_set: data[item].digit_per_field_set,
                            min_number: data[item].min_number,
                            max_number: data[item].max_number,
                            has_repetition: data[item].has_repetition,
                            days_availability: data[item].days_availability,
                            updated_at: date,
                        }
                        this.contents.push(game);
                    }
                })
                .catch(err => {
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

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
