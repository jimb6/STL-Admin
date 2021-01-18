<template>
    <v-main>
        <v-container>
            <div v-if="notifications.length > 0" v-for="notification in notifications">
                <Notification :notification="notification"></Notification>
            </div>

            <div class="row">
                <div class="col-12">
                    <GrossDatatable
                        :title="title"
                        :headers="headers"
                        :contents="contents"
                        :excelHeaders="excelHeaders"
                        :excelData="excelData"
                        :excelTitle="excelTitle"
                        :reportUrl="reportUrl"
                        :reportType="reportType"
                        :gameAbbreviations="gameAbbreviations"
                        @displayReports="displayGrossByDraw"
                    />
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>
import Notification from "../../components/Notification";
import GrossDatatable from "../../components/GrossDatatable";

export default {
    name: "ByDraw",
    components: {
        Notification,
        GrossDatatable
    },
    data: () => ({
        title: "Gross By Draw",
        headers: [
            {text: "Draw Period", value: "draw_period"},
            {text: "Game", value: "game_name"},
            {text: "Gross", value: "gross"},
            {text: "Commission", value: "commission"},
            {text: "Net", value: "net"},
            {text: "Hits", value: "hits"},
            {text: "Amount Hits", value: "amount_hits"},
            {text: "Collectible", value: "collectible"}
        ],
        contents: [],
        gameAbbreviations: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',
        reportUrl: '',
        reportType: 'byDraw',

        // Notification
        notifications: [],
    }),
    created() {
    },

    methods: {
        async displayGrossByDraw(item) {

            await axios.post('/api/v1/reports/overall-gross', {
                report_type: this.reportType,
                cluster_id: item.cluster_id,
                dates: item.dates
            })
                .then(response => {
                    const data = response.data.reports;
                    this.contents = [];

                    for (let i in data) {
                        this.contents.push({
                            draw_period: data[i].draw_period,
                            game_name: data[i].game_name,
                            gross: data[i].gross,
                            commission: data[i].commission,
                            net: data[i].net,
                            hits: data[i].hits,
                            amount_hits: data[i].amount_hits,
                            collectible: data[i].collectible,
                        });
                    }
                    // this.updateExcelFields("combination", dates);
                    console.log( response.data, "BY DRAW REPORTS" );
                }).catch(err => {
                    console.log(err)
                })

        },
    }
}
</script>

<style scoped>

</style>
