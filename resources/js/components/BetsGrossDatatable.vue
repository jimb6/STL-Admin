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
            :sort-by="['draw_date','draw_period']"
            :sort-desc="[true, true]"
            multi-sort
            @page-count="pageCount = $event"
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div style="width: 75% !important;">

                        <div class="flex">


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
                                class="mr-2"
                                :excelHeaders="excelHeaders"
                                :excelData="excelData"
                                :excelTitle="excelTitle"
                            />

                            <!-- PDF FILE -->
                            <Pdf
                                alt="Download pdf file"
                                :excelHeaders="excelHeaders"
                                :excelData="excelData"
                                :excelTitle="excelTitle"
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
                <tr :class="sumSingleField('collectible') < 0 ? 'myDanger': 'mySuccess'" style="letter-spacing: 1px;">
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
    name: 'BetsGrossDatatable',
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
    },

    data: () => ({
        // SEARCH AND PAGINATION
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 100,

        //  FILTERS
        clusterFilter: {},
        dates: [],
        modal: false,

        // DATATABLE

    }),

    created() {
        this.initialize()
    },

    methods: {

        initialize() {
            this.dates = [this.getCurrentDate(), this.getCurrentDate()]
            this.getClusters();
        },

        displayReports() {
            if (this.clusterFilter.selected !== '' && this.dates !== null && this.dates.length > 1)
                this.$emit('displayReports', [this.clusterFilter, this.dates])
        },

        getClusters() {
            axios.get('/api/v1/cluster-categorized')
                .then(response => {

                    let clusters = response.data.clusters
                    let clustersId = response.data.clustersId

                    this.clusterFilter = {
                        selected: {
                            text: "All",
                            value: clustersId['without-commission'].concat(clustersId['with-commission']),
                            type: "super"
                        },
                        options: [{
                            text: "All",
                            value: clustersId['without-commission'].concat(clustersId['with-commission']),
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
            return (this.contents.reduce((a, b) => a + (b[key] || 0), 0)).toLocaleString('en-US', {style: 'currency', currency: 'PHP',});
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
</style>

