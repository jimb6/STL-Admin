<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ title }}</h2>

        <!-- REPORTS TABLE -->
        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :item-class="getStatusBackground"
            :search="search"
            hide-default-footer
            :sort-by="['draw_date','draw_period', 'combination']"
            :sort-desc="[true, true, false]"
            multi-sort
            @page-count="pageCount = $event"
            :loading="loadingStatus"
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div style="width: 75% !important;">

                        <div class="flex">
                            <!-- REPORT TYPE FILTER -->
                            <v-select
                                v-model="reportTypeFilter.selected"
                                :items="reportTypeFilter.options"
                                item-text="text"
                                item-value="value"
                                label="Report Type"
                                class="mr-5"
                                return-object
                                @change="displayReports"
                            />

                            <!-- CLUSTER FILTER -->
                            <v-select
                                v-model="clusterFilter.selected"
                                :items="clusterFilter.options"
                                item-text="text"
                                item-value="value"
                                label="Cluster"
                                class="mr-5"
                                return-object
                                @change="displayReports"
                            >
                                <template v-slot:item="{ item }">
                                    <p :class="item.type">{{ item.text }}</p>
                                </template>
                            </v-select>

                            <!-- DRAW PERIOD FILTER -->
                            <v-select
                                v-model="drawPeriodFilter.selected"
                                :items="drawPeriodFilter.options"
                                item-text="text"
                                item-value="value"
                                label="Draw Period"
                                class="mr-5"
                                return-object
                                @change="displayReports"
                            />

                            <!-- DATE RANGE FILTER -->
                            <div class="mr-5">
                                <v-dialog
                                    ref="dialog"
                                    v-model="modal"
                                    :return-value.sync="dates"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="dateRangeText"
                                            label="Date Range"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="dates"
                                        scrollable
                                        range
                                        :max="getCurrentDate()"
                                    >
                                        <v-btn text color="primary" @click="modal = false">
                                            Cancel
                                        </v-btn>
                                        <v-btn text color="primary" @click="()=>{ $refs.dialog.save(dates); displayReports()}">
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </div>

                            <!-- EXCEL FILE -->
                            <Excel
                                v-if="hasExcelReport"
                                class="mr-2"
                                :excelHeaders="excelHeaders"
                                :excelData="excelData"
                                :excelTitle="excelTitle"
                                :reportsUrl="reportUrl"
                            />

                            <!-- PDF FILE -->
                            <Pdf
                                v-if="hasPdfReport"
                                alt="Download pdf file"
                                :excelHeaders="excelHeaders"
                                :excelData="excelData"
                                :excelTitle="excelTitle"
                                :reportsUrl="reportUrl"
                            />
                        </div>
                    </div>

                    <!-- SEARCH -->
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </div>
            </template>

            <!-- V-SLOTS  -->
            <template v-slot:body.prepend="{headers}">
                <tr :class="sumSingleField('collectible') < 0 ? 'myDanger': 'mySuccess'" style="letter-spacing: 1px;" v-if="reportTypeFilter.selected.value === 'General'">
                    <td v-for="(header,i) in headers" :key="i">
                        <div v-if="header.value === 'cluster'" style="text-transform: uppercase">
                            Grand Total
                        </div>

                        <div v-if="header.value === 'agent_id'" style="text-transform: uppercase">
                            Grand Total
                        </div>

                        <div v-if="header.value === 'gross'">
                            <b>{{ sumField('gross') }}</b>
                        </div>

                        <div v-if="header.value === 'commission'">
                            <b>{{ sumField('commission') }}</b>
                        </div>

                        <div v-if="header.value === 'net'">
                            <b>{{ sumField('net') }}</b>
                        </div>

                        <div v-if="header.value === 'hits'">
                            <b>{{ sumField('hits') }}</b>
                        </div>

                        <div v-if="header.value === 'amount_hits'">
                            <b>{{ sumField('amount_hits') }}</b>
                        </div>

                        <div v-if="header.value === 'payout'">
                            <b>{{ sumField('payout') }}</b>
                        </div>

                        <div v-if="header.value === 'collectible'">
                            <b>{{ sumField('collectible') }}</b>
                        </div>

                        <div v-else>
                            <!-- empty table cells for columns that don't need a sum -->
                        </div>
                    </td>
                </tr>
                <tr :class="sumSingleField('amount') < 0 ? 'myDanger': 'mySuccess'" style="letter-spacing: 1px;" v-if="reportTypeFilter.selected.value === 'Combination'">
                    <td v-for="(header,i) in headers" :key="i">
                        <div v-if="header.value === 'combination'" style="text-transform: uppercase">
                            Grand Total
                        </div>

                        <div v-if="header.value === 'amount'">
                            <b>{{ sumField('amount') }}</b>
                        </div>

                        <div v-else>
                            <!-- empty table cells for columns that don't need a sum -->
                        </div>
                    </td>
                </tr>
            </template>

            <template v-slot:item.gross="{item}">
                <p class="text-right">{{ item.gross.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.commission="{item}">
                <p class="text-right">{{ item.commission.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.net="{item}">
                <p class="text-right">{{ item.net.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.hits="{item}">
                <p class="text-right">{{ item.hits.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.amount_hits="{item}">
                <p class="text-right">{{ item.amount_hits.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.payout="{item}">
                <p class="text-right">{{ item.payout.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.collectible="{item}">
                <p class="text-right">{{ item.collectible.toFixed(2)  }}</p>
            </template>
            <template v-slot:item.valid="{item}">
                <v-avatar color="green" size="30" v-if="item.valid">
                    <v-icon dark small>
                        mdi-check
                    </v-icon>
                </v-avatar>
                <v-avatar color="red" size="30" v-if="!item.valid">
                    <v-icon dark small>
                        mdi-close
                    </v-icon>
                </v-avatar>
            </template>

        </v-data-table>

        <!-- PAGINATION -->
        <div class="flex-between cstm-row col2">
            <v-text-field
                :value="itemsPerPage"
                label="Items per page"
                type="number"
                min="-1"
                max="15"
                @input="itemsPerPage = parseInt($event, 10)"
                class="cstm-v-textfield small"
            ></v-text-field>
            <div>
                <v-pagination
                    v-model="page"
                    :length="pageCount"
                    prev-icon="mdi-menu-left"
                    next-icon="mdi-menu-right"
                    :total-visible="7"
                    circle
                ></v-pagination>
            </div>
        </div>
    </v-container>

</template>

<script>
import Excel from "./Excel";
import Pdf from "./Pdf";

export default {
    name: 'BetsReportDatatable',
    components: {
        Excel,
        Pdf
    },
    props: {
        title: String,
        headers: Array,
        contents: Array,

        excelHeaders: Array,
        excelData: Array,
        excelTitle: String,
        reportUrl: String,
        loadingStatus: Boolean,

        hasExcelReport: Boolean,
        hasPdfReport: Boolean,
    },

    data: () => ({
        // SEARCH AND PAGINATION
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 100,

        //  FILTERS
        reportTypeFilter: {
            selected: {text: "", value: ""},
            options: [{text: "General", value: "General"}, {text: "Combination", value: "Combination"}]
        },
        clusterFilter: {},
        drawPeriodFilter: {},
        dates: [],
        modal: false,

        // DATATABLE

    }),

    created() {
        this.initialize();
    },

    methods: {

        initialize() {
            this.dates = [this.getCurrentDate(), this.getCurrentDate()]
            this.getClusters();
            this.getDrawPeriods();
        },

        displayReports() {
            if (this.drawPeriodFilter.selected !== '' && this.clusterFilter.selected !== '' && this.reportTypeFilter.selected !== '' && this.dates !== null && this.dates.length > 1)
                this.$emit('displayReports', [this.reportTypeFilter, this.clusterFilter, this.drawPeriodFilter, this.dates])
        },

        displayGeneralReports() {

        },

        displayCombinationReports() {

        },

        getClusters() {
            axios.get('/api/v1/cluster-categorized')
                .then(response => {

                    console.log(response)
                    let clusters = response.data.clusters
                    let clustersId = response.data.clustersId

                    let withoutCommission = clustersId['without-commission']?clustersId['without-commission']:[]
                    let withCommission = clustersId['with-commission']?clustersId['with-commission']:[]

                    let withoutCommissionId = clustersId['without-commission']?clustersId['without-commission']:[]
                    let withCommissionId = clustersId['with-commission']?clustersId['with-commission']:[]

                    this.clusterFilter = {
                        selected: {
                            text: "All",
                            value: withoutCommission.concat(withCommission),
                            type: "super"
                        },
                        options: [{
                            text: "All",
                            value: withoutCommissionId.concat(withCommissionId),
                            type: "super"
                        }],
                    }

                    this.clusterFilter.options.push({
                        text: "Clusters w/o Commission",
                        value: clustersId['without-commission'],
                        type: "super"
                    })
                    for (let item in clusters['without-commission']) {
                        this.clusterFilter.options.push({
                            text: clusters['without-commission'][item].name,
                            value: [clusters['without-commission'][item].id],
                            type: "sub"
                        })
                    }

                    this.clusterFilter.options.push({
                        text: "Clusters w/ Commission",
                        value: clustersId['with-commission'],
                        type: "super"
                    })
                    for (let item in clusters['with-commission']) {
                        this.clusterFilter.options.push({
                            text: clusters['with-commission'][item].name,
                            value: [clusters['with-commission'][item].id],
                            type: "sub"
                        })
                    }
                }).catch(err => {
                console.log(err)
            })
        },

        getDrawPeriods() {
            axios.get('/api/v1/draw-periods-categorized/' + this.title)
                .then(response => {
                    console.log(response.data.drawPeriods, '<<<< DRAW PERIOD')
                    let drawPeriods = response.data.drawPeriods
                    if (drawPeriods.length > 1){
                        let ids = []
                        for (let item in drawPeriods) {
                            ids.push(drawPeriods[item].id)
                        }
                        this.drawPeriodFilter = {
                            selected: {text: "All", value: ids},
                            options: [{text: "All", value: ids}],
                        }
                        for (let item in drawPeriods) {
                            let drawTime = new Date('1/1/2021 ' + drawPeriods[item].draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                            this.drawPeriodFilter.options.push({
                                text: drawTime,
                                value: [drawPeriods[item].id]
                            })
                        }
                    } else {
                        let drawTime = new Date('1/1/2021 ' + drawPeriods[0].draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3");
                        this.drawPeriodFilter = {
                            selected: {text: drawTime, value: [drawPeriods[0].id]},
                            options: [{text: drawTime, value: [drawPeriods[0].id]}],
                        }
                    }

                    console.log(response, "DRAW PERIOD")
                }).catch(err => {
                console.log(err)
            })
        },

        getCurrentDate() {
            let date = new Date();
            const month = date.toLocaleString('default', {month: 'numeric'});
            date = date.getFullYear() + "-" + (month < 10 ? "0" + month : month) + "-" + (date.getDate() < 10 ? "0" + date.getDate() : date.getDate());
            return date
        },

        //  COLOR
        getStatusBackground(item) {
            if( item.collectible < 0 ) {
                return "myWarning myTr";
            } else {
                return "myTr"
            }
        },

        updateCloseCombination(item) {
            this.editedIndex = this.contents.indexOf(item);
            item['index'] = this.editedIndex;
            if (item.status === "OPEN") {
                this.$emit("storeCloseCombination", item);
            } else if (item.status === "CLOSED") {
                this.$emit("destroyCloseCombination", item);
            }
        },

        sumField(key) {
            // sum data in give key (property)
            return "₱ " + (this.contents.reduce((a, b) => a + (b[key] || 0), 0)).toLocaleString('en-US', { minimumFractionDigits: 2 ,});
        },

        sumSingleField(key) {
            // sum data in give key (property)
            return (this.contents.reduce((a, b) => a + (b[key] || 0), 0));
        }


    },

    computed: {
        dateRangeText() {
            this.dates.sort();
            return this.dates.join(' ~ ')
        },
    },


};
</script>

<style scoped>
.v-list-item p.super {
    font-weight: 600;
}

.v-list-item p.sub {
    padding-left: 20px;
}
span.v-data-table-header__sort-badge {
    display: none !important;
}
</style>

