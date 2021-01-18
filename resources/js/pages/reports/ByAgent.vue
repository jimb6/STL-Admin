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
                        @displayReports="displayGrossByAgent"
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
    name: "ByAgent",
    components: {
        Notification,
        GrossDatatable
    },
    data: () => ({
        title: "Gross By Agent",
        headers: [],
        contents: [],
        gameAbbreviations: [],

        excelHeaders: [],
        excelData: [],
        excelTitle: '',
        reportUrl: '',
        reportType: 'byAgent',

        // Notification
        notifications: [],
    }),
    created() {

    },

    methods: {
        async displayGrossByAgent(item) {

            await axios.post('/api/v1/reports/overall-gross', {
                report_type: this.reportType,
                cluster_id: item.cluster_id,
                dates: item.dates
            })
            .then(response => {
                const data = response.data.reports;
                this.contents = [];
                this.gameAbbreviations = response.data.game_abbreviations.split(',').reverse();

                if(this.headers.length === 0){
                    this.headers.push(
                        {text: "Agent Name", value: "agent_name"},
                        {text: "Device Code", value: "device_code"}
                    );
                    for(let i in this.gameAbbreviations) {
                        this.headers.push({text: this.gameAbbreviations[i], value: this.gameAbbreviations[i]})
                    }
                    this.headers.push({text: "Total Gross", value: "total_gross"});
                }

                for (let i in data) {
                    let item = {};
                        item['agent_name'] = data[i].agent_name;
                        item['device_code'] = data[i].device_code;
                        item['total_gross'] = data[i].total_gross ? data[i].total_gross : 0;
                    for(let j in this.gameAbbreviations) {
                        item[this.gameAbbreviations[j]] = data[i][this.gameAbbreviations[j]] ? data[i][this.gameAbbreviations[j]]:0;
                    }
                    this.contents.push(item);
                }
                // this.updateExcelFields("combination", dates);
            }).catch(err => {
                console.log(err)
            })

        },
    }
}
</script>

<style scoped>

</style>
