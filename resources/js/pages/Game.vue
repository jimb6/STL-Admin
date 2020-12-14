<template>
    <v-main>
        <v-container>
            <v-tabs>
                <v-tab>Table View</v-tab>
                <v-tab>Card View</v-tab>
                <v-tab-item>
                    <DataTable
                        :tableName="tableName"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        @storeUser="storeGame($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyGame($event)"
                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :tableName="tableName"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        @storeUser="storeGame($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyGame($event)"
                    />
                </v-tab-item>
            </v-tabs>
        </v-container>
    </v-main>
</template>

<script>
import DataTable from "../components/DataTable";
import Card2 from "../components/Card2";
import Vue from "vue";
import Vuetify from 'vuetify'

Vue.use(Vuetify)

export default {
    name: "Game",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
    },

    data: () => ({
        tableName: "Games",
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
            {label: "Has Repetition", field: "has_repetition", value: "", type: "select", options: ["True", "False"]},
            {
                label: "Days Availability",
                field: "days_availability",
                value: "",
                type: "chips",
                options: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
        ],

        editedItem: {},
        address: Array,
    }),
    created() {
        this.displayGames();
        // this.getClusters();
    },
    methods: {
        async displayGames() {
            const response = await axios.get('games/?').catch(err => {
                console.log(err)
            });
            let game = {};
            const data = response.data.games;
            let date = '';
            let count = 0;
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
        },
        async storeGame(item) {
            const response = await axios.post('games/?',
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
                }).catch(err => console.log(err))
            await this.displayGames()
        },
        async updateUser() {

        },
        async destroyGame(item) {
            const response = await axios.delete('games/?' + item.id).catch(err => console.log(err))
            await this.displayGames();
        },

        async getClusters() {
            const response = await axios.get('clusters/?').catch(err => console.log(err))
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
}
</script>
