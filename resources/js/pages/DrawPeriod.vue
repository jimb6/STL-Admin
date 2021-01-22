<template>
    <div>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>
        <DataTable
            :title="title"
            :headers="headers"
            :contents="contents"
            :fillable="fillable"
            @storeModel="storeDrawPeriod($event)"
            @updateModel="updateDrawPeriod($event)"
            @destroyModel="destroyDrawPeriod($event)"
            @updateStatus="closeDrawPeriod($event)"
            :canAdd="canAdd"
            :canEdit="canEdit"
            :canDelete="canDelete"
            :loadingRequest="loadingRequest"
        />
    </div>
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
            {text: "Start", value: "open_time"},
            {text: "End", value: "close_time"},
            {text: "Status", value: "isClosed"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Draw Time", field: "draw_time", value: "", type: "timepicker"},
            {label: "Draw Type", field: "draw_type", value: "", type: "select", options: ["STL", "National"]},
            {label: "Games", field: "games", value: "", type: "chips", options: Array},
            {label: "Open Time", field: "open_time", value: "", type: "timepicker"},
            {label: "Close Time", field: "close_time", value: "", type: "timepicker"},
        ],
        editedItem: {},
        notifications: [],

        loadingRequest: "okay",

        canAdd: true,
        canEdit: true,
        canDelete: true,
    }),
    created() {
        this.displayDrawPeriods();
        this.getAllGames();
    },
    methods: {
        async displayDrawPeriods() {
            await axios.get('/api/v1/draw-periods')
                .then(response => {
                    let drawPeriod = {};
                    const data = response.data.drawPeriods;
                    let count = 0;
                    this.contents = []
                    for (let item in data) {
                        let games = [];
                        for (let gameItem in data[item].games) {
                            games.push(data[item].games[gameItem].description)
                        }
                        count++;
                        drawPeriod = {
                            count: count,
                            id: data[item].id,
                            draw_time: data[item].draw_time,
                            draw_type: data[item].draw_type,
                            open_time: data[item].open_time,
                            close_time: data[item].close_time,
                            updated_at: data[item].updated_at,
                            games: games,
                            isClosed: data[item].status
                        }
                        this.contents.push(drawPeriod);
                    }
                })
                .catch(err => {
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        async getAllGames() {
            axios.get('/api/v1/games')
                .then(response => {
                    let games = [];
                    let data = response.data.games;
                    for (let index in data) {
                        games.push(data[index].description)
                    }
                    for (let index in this.fillable) {
                        if (this.fillable[index].field == 'games') {
                            this.fillable[index].options = games;
                        }
                    }
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully added!", "error", "400");
                });
        },

        async storeDrawPeriod(item) {
            axios.post('/api/v1/draw-periods',
                {
                    'draw_time': item.draw_time,
                    'draw_type': item.draw_type,
                    'games': item.games,
                    'open_time': item.open_time,
                    'close_time': item.close_time
                })
                .then(response => {
                    this.addNotification(item.draw_time + " added successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully added!", "error", "400");
                });
            await this.displayDrawPeriods()
        },

        async updateDrawPeriod(item) {
            await axios.put('/api/v1/draw-periods/' + item.id, {
                'draw_time': item.draw_time,
                'draw_type': item.draw_type,
                'games': item.games,
                'open_time': item.open_time,
                'close_time': item.close_time
            })
                .then(response => {
                    this.addNotification(item.draw_time + " updated successfully!", "success", response.status)
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully updated!", "error", err.status)
                });
            await this.displayDrawPeriods();
        },

        async destroyDrawPeriod(item) {
            await axios.delete('/api/v1/draw-periods/' + item.id)
                .then(response => {
                    this.addNotification(item.draw_time + " deleted successfully!", "success", response.status)
                })
                .catch(err => {
                    this.addNotification(item.draw_time + " unsuccessfully deleted!", "error", err.status)
                });
            await this.displayDrawPeriods();
        },

        async closeDrawPeriod(item) {
            axios.put('/api/v1/close-draw-period/' + item.id, {
                status: item.isClosed
            })
                .then(response => {
                    this.displayDrawPeriods();
                    console.log('response');
                }).catch(err => {
                this.$nextTick(function () {
                    this.contents[item.index].isClosed = !item.isClosed;
                });
            })
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
