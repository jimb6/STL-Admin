<template>
    <div>
        <div v-if="notifications.length > 0" v-for="notification in notifications">
            <Notification :notification="notification"></Notification>
        </div>

        <BetsReportDatatable
            class="singleBetTable"
            :title="game"
            :headers="headers"
            :contents="contents"
            :excelHeaders="excelHeaders"
            :excelData="excelData"
            :excelTitle="excelTitle"
            :reportUrl="reportUrl"
            :loadingStatus="loadingStatus"
            @displayReports="displayReports($event)"
            :hasExcelReport="true"
            :hasPdfReport="true"
        />
    </div>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import Notification from "../components/Notification";
import BetsReportDatatable from "../components/BetsReportDatatable";

export default {
    name: "Reports",
    props: {
        game: String,
    },
    components: {
        Notification,
        BetsReportDatatable
    },
    data: () => ({
        headers: [{text: "", value: ""},],
        contents: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',
        loadingStatus: true,

        // Notification
        notifications: [],
        reportUrl: '',
    }),
    created() {
    },

    methods: {

        displayReports(config) {
            let reportType = config[0]
            let clusterType = config[1]
            let drawPeriodType = config[2]
            let dates = config[3]

            console.log(reportType.selected, "REPORT TYPE")
            console.log(clusterType.selected, "CLUSTER")
            console.log(drawPeriodType.selected, "DRAW PERIOD")
            console.log(clusterType.selected.type, "SELECTED")

            if (reportType.selected.text === "General") {
                console.log("General Reports")
                if (clusterType.selected.type === "super") {
                    this.headers = [
                        {text: "Cluster Name", value: "cluster"},
                        {text: "Draw Date", value: "draw_date"},
                        {text: "Draw Period", value: "draw_period"},
                        {text: "Gross", value: "gross"},
                        {text: "Commission", value: "commission"},
                        {text: "Net", value: "net"},
                        {text: "Hits", value: "hits"},
                        {text: "Amount Hits", value: "amount_hits"},
                        {text: "Payout", value: "payout"},
                        {text: "Collectible", value: "collectible"},
                        {text: "Validity", value: "valid"},
                    ]

                } else if (clusterType.selected.type === "sub") {
                    this.headers = [
                        {text: "Agent Name", value: "agent_name"},
                        {text: "Draw Date", value: "draw_date"},
                        {text: "Draw Period", value: "draw_period"},
                        {text: "Gross", value: "gross"},
                        {text: "Commission", value: "commission"},
                        {text: "Net", value: "net"},
                        {text: "Hits", value: "hits"},
                        {text: "Amount Hits", value: "amount_hits"},
                        {text: "Payout", value: "payout"},
                        {text: "Collectible", value: "collectible"},
                        {text: "Validity", value: "valid"},
                    ]
                }
                this.displayGeneralReports(clusterType.selected.value, drawPeriodType.selected.value, dates)
            } else if (reportType.selected.text === "Combination") {
                this.headers = [
                    {text: "Combination", value: "combination"},
                    {text: "Bet Amount", value: "amount"},
                ]
                this.displayCombinationReports(clusterType.selected.value, drawPeriodType.selected.value, dates)
            }
        },

        async displayCombinationReports(clusterId, drawPeriodId, dates) {

            this.$nextTick(() => {
                this.loadingStatus = true;
            });

            await axios.post('/api/v1/bets-reports/combination', {
                cluster_id: clusterId,
                draw_period_id: drawPeriodId,
                game: this.game,
                dates: dates
            })
                .then(response => {
                    console.log(response)
                    this.reportUrl = response.data.reports_url
                    const data = response.data.bets;
                    this.contents = [];
                    for (let item in data) {
                        this.contents.push({
                            combination: data[item].combination,
                            amount: data[item].amount,
                        });
                    }
                    this.updateExcelFields("combination", dates);

                    this.$nextTick(() => {
                        this.loadingStatus = false;
                    });
                    console.log(response.data, "COMBINATION REPORTS")
                }).catch(err => {
                    console.log(err)
                })

        },

        async displayGeneralReports(clusterId, drawPeriodId, dates) {
            console.log(clusterId.length, "LENGTH <<<<<<<<<<<<<<<<<<")
            // console.log(clusterId.count(), "COUNT <<<<<<<<<<<<<<<<<<")

            axios.post('/api/v1/bets-reports/general', {
                cluster_id: clusterId,
                draw_period_id: drawPeriodId,
                game: this.game,
                dates: dates
            })
                .then(response => {
                    const data = response.data.generalReports;
                    this.contents = [];
                    this.reportUrl = response.data.report_url
                    console.log(this.reportUrl)
                    console.log(response, "RESPONSE <<<<<<<<<<<<<<<<<<")
                    for (let i in data) {
                        let agent = []
                        if (clusterId.length === 1)
                            this.contents.push({
                                agent_name: data[i].agent_name,
                                cluster: data[i].cluster_name,
                                draw_date: data[i].draw_date,
                                draw_period: data[i].draw_period,
                                gross: data[i].gross,
                                commission: data[i].commission,
                                net: data[i].net,
                                hits: data[i].hits,
                                amount_hits: data[i].amount_hits,
                                payout: 0,
                                collectible: data[i].collectible,
                                valid: true,
                            });
                        else
                            this.contents.push({
                                cluster: data[i].cluster_name,
                                draw_date: data[i].draw_date,
                                draw_period: data[i].draw_period,
                                gross: data[i].gross,
                                commission: data[i].commission,
                                net: data[i].net,
                                hits: data[i].hits,
                                amount_hits: data[i].amount_hits,
                                payout: 0,
                                collectible: data[i].collectible,
                                valid: true,
                                // agent_id: data[cluster][drawTime].user.id,
                                // agent_name: data[cluster][drawTime].user.name,
                            });

                    }
                }).catch(err => {
                console.log(err)
            })

        },

        // NOTIFICATION
        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        },
        getDateToday(date) {
            date = (date) ? date : new Date();
            const month = date.toLocaleString('default', {month: 'long'});
            date = month + " " + date.getDate() + ", " + date.getFullYear() + " - " + date.toLocaleTimeString();
            return date;
        },

        updateExcelFields(type, dates) {
            this.excelHeaders = []
            this.excelData = []
            this.excelTitle = this.game + " (" + dates.join(" ~ ") + ")";
            this.excelHeaders = [
                {name: "Combination", subheader: []},
                {name: "Bet Amount", subheader: []},
            ];

            let fields = ["combination", "amount"]
            this.excelData.push({items: this.contents, fields: fields})
        },
    }
}
</script>
