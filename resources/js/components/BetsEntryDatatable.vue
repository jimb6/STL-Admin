<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ title }}</h2>

        <!-- ENTRIES TABLE -->
        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :item-class="getStatusBackground"
            :search="search"
            hide-default-footer
            :sort-by="['draw_period', 'created_at']"
            :sort-desc="[true, true]"
            @page-count="pageCount = $event"
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div style="width: 75% !important;">

                        <div class="flex">
                            <!-- REALTIME FILTER -->
                            <v-switch
                                class="mr-5 labelNoMargin"
                                v-model="isRealTime"
                                label="Realtime"
                                inset
                            ></v-switch>

                            <!-- DRAW PERIOD FILTER -->
                            <v-select
                                v-model="drawPeriodFilter.selected"
                                :items="drawPeriodFilter.options"
                                item-text="text"
                                item-value="value"
                                label="Draw Period"
                                class="mr-5"
                                return-object
                                @change="displayBetEntries()"
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
                                        <v-btn text color="primary" @click="()=>{ $refs.dialog.save(dates); displayBetEntries()}">
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

            <!-- V-SLOTS -->
            <template v-slot:item.draw_period="{ item }">
                {{ new Date('1/1/2021 ' + item.draw_period).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") }}
            </template>

            <template v-slot:item.combinations="{ item }">
                <v-chip v-for="bet in item.combinations" :key="bet.id" dark small class="mr-2 my-2">
                    <p><span class="mr-2">{{ bet.combination }}</span><b style="font-weight: 700;">- {{ bet.amount }}</b></p>
                </v-chip>
            </template>

            <template v-slot:item.status="{ item }">
                <v-switch
                    :disabled="item.status"
                    color="red"
                    @change="updateReprintActions(item)"
                ></v-switch>
            </template>

            <template v-slot:item.reprint="{ item }">
                <v-switch
                    :disabled="item.reprint"
                    v-model="item.reprint"
                    color="white"
                    @change="updateReprintActions(item)"
                    :loading="loadingRequest"
                ></v-switch>
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
    name: 'BetsEntryDatatable',
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
        loadingRequest: String,
    },

    data: () => ({
        // SEARCH AND PAGINATION
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 100,

        //  FILTERS
        drawPeriodFilter: {},
        dates: [],
        modal: false,
        isRealTime: true,
    }),

    created() {
        this.initialize();
    },

    methods: {

        initialize() {
            this.dates = [this.getCurrentDate(), this.getCurrentDate()]
            this.getDrawPeriods();
        },

        updateReprintActions(action){
            console.log("REPRINTING....")
        },

        displayBetEntries() {
            console.log( "DISPLAY BET ENTRIES" );
            if (this.dates.length === 2 && this.drawPeriodFilter.selected !== ''){
                this.$emit('viewModel', [this.drawPeriodFilter, this.dates])
            }
        },

        getDrawPeriods() {
            axios.get('/api/v1/draw-periods-categorized/' + this.title)
                .then(response => {
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

        // EDIT BELOW ==================================================================================================
        //  COLOR
        getStatusBackground(item) {
            if( item.reprint ) {
                item.isClosed = true;
                return "myInfo myTr";
            } else {
                return "myTr";
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
<style>
.labelNoMargin label {
    margin: unset !important;
}
.singleBetTable .myInfo td{
    color: #ffffff;
}
</style>

