<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ title }}</h2>

        <!-- WINNING COMBINATIONS TABLE -->
        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :search="search"
            hide-default-footer
            :sort-by="['combination']"
            :sort-desc="[false]"
            @page-count="pageCount = $event"
            :loading="loadingStatus"
            loading-text="Loading... Please wait"
        >

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div style="width: 75% !important;">

                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <div class="flex">
                                    <!-- ADD BUTTON -->
                                    <v-btn color="blue" class="text-white mr-2" v-bind="attrs" v-on="on">
                                        <v-icon small class="mr-2">
                                            mdi-plus
                                        </v-icon>
                                        Add New {{ title }}
                                    </v-btn>

                                    <!-- EXCEL FILE -->
                                    <Excel
                                        class="mr-2"
                                        :excelHeaders="excelHeaders"
                                        :excelData="excelData"
                                        :excelTitle="excelTitle"
                                    />

                                    <!-- PDF FILE -->
                                    <Pdf
                                        class="mr-5"
                                        alt="Download pdf file"
                                        :excelHeaders="excelHeaders"
                                        :excelData="excelData"
                                        :excelTitle="excelTitle"
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
                                                <v-btn text color="primary" @click="()=>{ $refs.dialog.save(dates); displayBetWinningCombinations()}">
                                                    OK
                                                </v-btn>
                                            </v-date-picker>
                                        </v-dialog>
                                    </div>


                                </div>
                            </template>

                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" v-for="(item, index) in fillable" :key="index">
                                                <v-text-field
                                                    v-if="item.type === 'input'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label"
                                                />

                                                <v-text-field
                                                    v-if="item.type === 'input-password'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label"
                                                    type="password"
                                                />

                                                <v-select
                                                    v-if="item.type === 'select'"
                                                    v-model="editedItem[item.field]"
                                                    :items="item.options"
                                                    item-text="draw_time"
                                                    item-value="id"
                                                    :label="item.label"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn outlined color="blue" @click="close">
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue" class="text-white" @click="saveItem()">
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

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
            <template v-slot:item.actions="{ item }">
                <v-icon class="mr-2" @click="verifyItem(item)" v-if="!item.verifiedAt">
                    mdi-thumb-up-outline
                </v-icon>
                <v-icon class="mr-2" v-if="item.verifiedAt" color="green">
                    mdi-thumb-up-outline
                </v-icon>
                <v-icon class="mr-2" @click="editItem(item)" v-show="( new Date(item.drawDate).toLocaleDateString() === new Date().toLocaleDateString() )">
                    mdi-pencil
                </v-icon>
            </template>

            <template v-slot:item.drawPeriod="{ item }">
                {{ new Date('1/1/2021 ' + item.drawPeriod).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") }}
            </template>

            <template v-slot:item.drawDate="{ item }">
                {{ new Date(item.drawDate).toLocaleString(undefined, { year: 'numeric', month: 'long', day: '2-digit' }) }}
            </template>

            <template v-slot:item.verifiedAt="{ item }">
                <v-avatar color="green" size="30" v-if="item.verifiedAt">
                    <v-icon dark small>
                        mdi-check
                    </v-icon>
                </v-avatar>
                <v-avatar color="red" size="30" v-if="!item.verifiedAt">
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
    name: 'BetsWinningCombinationDatatable',
    components: {
        Excel,
        Pdf
    },
    props: {
        title: String,
        headers: Array,
        contents: Array,
        fillable: Array,
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
        loadingStatus: true,

        //  FILTERS
        dates: [],
        modal: false,

        // CRUD VARIABLES
        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {},
        defaultItem: {},
        verifiedItem: {},
    }),

    created() {
        this.initialize();
        this.dates = [this.getCurrentDate(), this.getCurrentDate()]
    },

    updated() {
        this.loadingStatus = false;
    },

    methods: {
        initialize() {
            for (let index in this.fillable) {
                this.editedItem[this.fillable[index].field] = this.fillable[index].value
                this.defaultItem[this.fillable[index].field] = this.fillable[index].value
            }
        },
        editItem(item) {
            this.initialize();
            this.editedIndex = this.contents.indexOf(item)
            for (let index in this.editedItem) {
                this.editedItem[index] = this.contents[this.editedIndex][index]
            }
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        verifyItem(item) {
            this.initialize();
            this.editedIndex = this.contents.indexOf(item)
            this.verifiedItem = item;
            this.dialog = true
        },
        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
                this.verifiedItem = {}
            })
        },
        saveItem() {
            if (this.editedIndex > -1) {
                // VERIFY AND UPDATE
                if (Object.keys(this.verifiedItem).length === 0 && this.verifiedItem.constructor === Object) {
                    this.editedItem["id"] = this.contents[this.editedIndex].id;
                    this.editedItem['dates'] = this.dates;
                    this.$emit('updateWinningCombination', this.editedItem);
                } else {
                    this.editedItem["id"] = this.contents[this.editedIndex].id;
                    this.editedItem['verifiedItem'] = this.verifiedItem;
                    this.$emit('verifyWinningCombination', this.editedItem);
                }
            } else {
                // SAVE
                this.$emit('storeWinningCombination', this.editedItem);
            }
            this.close()
        },


        // CRUD OPERATIONS
        displayBetWinningCombinations() {
            if(this.dates !== null && this.dates.length > 1)
                this.$emit('displayBetWinningCombinations', this.dates)
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
            switch (item.status) {
                case "SOLD OUT":
                    item.isClosed = true;
                    return "myDanger myTr"
                case "OPEN":
                    return "myTr"
                case "CLOSED":
                    item.isClosed = true;
                    return "myWarning myTr"
                default:
                    return "myDanger"
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

    },

    computed: {
        dateRangeText() {
            this.dates.sort();
            return this.dates.join(' ~ ')
        },
        formTitle() {
            let title = this.title;
            if (Object.keys(this.verifiedItem).length === 0 && this.verifiedItem.constructor === Object) {
                return this.editedIndex === -1 ? 'Add New ' + title + '\'s Winning Combi' : 'Edit ' + title + '\'s Winning Combi';
            } else {
                return 'Verify ' + title + '\'s Winning Combi';
            }

        },
    },
};
</script>

<style>
.labelNoMargin label {
    margin: unset !important;
}
</style>

