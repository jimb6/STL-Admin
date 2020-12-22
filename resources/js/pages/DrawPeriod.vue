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
                        @storeModel="storeDrawPeriod($event)"
                        @updateModel="updateDrawPeriod($event)"
                        @destroyModel="destroyDrawPeriod($event)"
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
                        @storeModel="storeDrawPeriod($event)"
                        @updateModel="updateDrawPeriod($event)"
                        @destroyModel="destroyDrawPeriod($event)"
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
    name: "DrawPeriod",
    components: {
        Card2,
        DataTable,
        Notification,
    },

    data: () => ({
        title: "Draw Period",
        headers: [
            {text: "#", value: "count"},
            {text: "Draw Time", value: "draw_time"},
            {text: "Draw Type", value: "draw_type"},
            {text: "Last Update", value: "updated_at"},
            {text: "Games", value: "games"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Draw Time", field: "draw_time", value: "", type: "timepicker"},
            {label: "Draw Type", field: "draw_type", value: "", type: "select", options: ["Local", "National"]},
        ],
        editedItem: {},
        notifications: [],

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayDrawPeriods();
    },
    methods: {
        async displayDrawPeriods() {
            await axios.get('/api/v1/draw-periods')
                .then(response=> {
                    console.log(response)
                    let drawPeriod = {};
                    const data = response.data.drawPeriods;
                    let count = 0;
                    this.contents = []
                    for (let item in data) {
                        let games = [];
                        for (let gameItem in data[item].games){
                            games.push(data[item].games[gameItem].description)
                        }
                        count++;
                        drawPeriod = {
                            count: count,
                            id: data[item].id,
                            draw_time: data[item].draw_time,
                            draw_type: data[item].draw_type,
                            updated_at: data[item].updated_at,
                            games: games
                        }
                        this.contents.push(drawPeriod);
                    }
                })
                .catch(err => {
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        async storeDrawPeriod(item) {
            axios.post('/api/v1/draw-periods',
                {
                    'draw_time': item.draw_time,
                    'draw_type': item.draw_type,
                })
                .then(response => {
                    this.addNotification(item.draw_time + " added successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully added!", "error", "400");
                });
            await this.displayDrawPeriods()
        },

        async updateDrawPeriod() {

        },

        async destroyDrawPeriod(item) {
            const response = await axios.delete('/api/draw-periods/' + item.id)
                .then(response => {
                    this.addNotification(item.draw_time + " deleted successfully!", "success", "200")
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully deleted!", "error", "400")
                });
            await this.displayDrawPeriods();
        },

        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },


    }
}
</script>
