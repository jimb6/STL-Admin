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
                        @storeUser="storeDrawPeriod($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyDrawPeriod($event)"
                    />
                </v-tab-item>
                <v-tab-item>
                    <Card2
                        :tableName="tableName"
                        :contents="contents"
                        :headers="headers"
                        :fillable="fillable"
                        @storeUser="storeDrawPeriod($event)"
                        @changeAddress="changeAddress($event)"
                        @destroyUser="destroyDrawPeriod($event)"
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
    name: "DrawPeriod",
    props: {
        userData: JSON,
    },
    components: {
        Card2,
        DataTable,
    },

    data: () => ({
        tableName: "Draw Periods",
        headers: [
            {text: "#", value: "count"},
            {text: "Draw Time", value: "draw_time"},
            {text: "Draw Type", value: "draw_type"},
            {text: "Last Update", value: "updated_at"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
        fillable: [
            {label: "Draw Time", field: "draw_time", value: "", type: "timepicker"},
            {label: "Draw Type", field: "draw_type", value: "", type: "select", options: ["Local", "National"]},
        ],

        editedItem: {},
        address: Array,
    }),
    created() {
        this.displayDrawPeriods();
        // this.getClusters();
    },
    methods: {
        async displayDrawPeriods() {
            const response = await axios.get('draw-periods/?').catch(err => {
                console.log(err)
            });
            let drawPeriod = {};
            const data = response.data.drawPeriods;
            let date = '';
            let count = 0;
            this.contents = []
            for (let item in data) {
                date = this.getDateToday(new Date(data[item].updated_at));
                count++;
                drawPeriod = {
                    count: count,
                    id: data[item].id,
                    draw_time: data[item].draw_time,
                    draw_type: data[item].draw_type,
                    updated_at: date,
                }
                this.contents.push(drawPeriod);
            }
        },
        async storeDrawPeriod(item) {
            const response = await axios.post('draw-periods/?',
                {
                    'draw_time': item.draw_time,
                    'draw_type': item.draw_type,
                }).catch(err => {
                    console.log(err)
            })
            await this.displayDrawPeriods()
        },
        async updateUser() {

        },
        async destroyDrawPeriod(item) {
            const response = await axios.delete('draw-periods/?' + item.id).catch(err => console.log(err))
            await this.displayDrawPeriods();
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
