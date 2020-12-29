<template>
    <v-container class="cstm-vuetify-table">
        <h3>{{ title }}</h3>



        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :item-class="getStatusBackground"
            :search="search"
            hide-default-footer
            :sort-by="['amount', 'status']"
            :sort-desc="[true, true]"
            @page-count="pageCount = $event"
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2" v-if="hasTopHeader">
                    <div>
                        <v-row class="flex">
                            <v-col cols="4">
                                <v-dialog
                                    ref="dialog"
                                    v-model="modalDate"
                                    :return-value.sync="date"
                                    persistent
                                    width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="date"
                                            label="Date"
                                            prepend-icon="mdi-calendar"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="date"
                                        :max="maxDate"
                                        scrollable
                                    >
                                        <v-spacer></v-spacer>
                                        <v-btn text color="primary" @click="modalDate = false">
                                            Cancel
                                        </v-btn>
                                        <v-btn text color="primary" @click="displayBets(date)">
                                            OK
                                        </v-btn>
                                    </v-date-picker>
                                </v-dialog>
                            </v-col>

                            <v-col cols="4">
                                <v-select
                                    v-model="selectedDrawPeriod"
                                    :items="drawPeriods"
                                    item-text="name"
                                    item-value="id"
                                    label="Draw Period"
                                    return-object/>
                            </v-col>
                        </v-row>
                    </div>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </div>
                <div class="flex-between cstm-row col3" v-if="!hasTopHeader">
                    <v-text-field
                        :value="itemsPerPage"
                        label="Items per page"
                        type="number"
                        min="-1"
                        max="15"
                        @input="itemsPerPage = parseInt($event, 10)"
                        class="cstm-v-textfield small"
                    ></v-text-field>
                    <h2 class="cstm-price">₱ {{ total }}</h2>
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
            </template>

            <template v-slot:item.is_rumbled="{ item }">
                <v-checkbox
                    :input-value="item.is_rumbled"
                    disabled
                ></v-checkbox>
            </template>

            <template v-slot:item.is_voided="{ item }">
                <v-checkbox
                    :input-value="item.is_voided"
                    disabled
                ></v-checkbox>
            </template>

            <template v-slot:item.isClosed="{ item }">
                <v-switch
                    :disabled="item.status=='SOLD OUT'"
                    v-model="item.isClosed"
                    color="white"
                    @change="updateCloseCombination(item)"
                    :loading="loadingRequest"
                ></v-switch>
            </template>
        </v-data-table>


        <div class="flex-between cstm-row col2" v-if="hasTopHeader">
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
import Address from "./Address";

export default {
    name: 'BetsDatatable',
    components: {
        Address
    },
    props: {
        title: String,
        total: "",
        headers: Array,
        contents: Array,
        fillable: Array,
        canAdd: Boolean,
        canEdit: Boolean,
        canDelete: Boolean,
        hasTopHeader: Boolean,
        loadingRequest: String,
    },

    data: () => ({
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 100,

        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {},
        defaultItem: {},

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',

        birthdate: null,

        time: null,
        menuTime: false,


        date: new Date().toISOString().substr(0, 10),
        maxDate: "",
        menuDate: false,
        modalDate: false,
        menu2Date: false,

        selectedDrawPeriod: "",
        drawPeriods: ["L-10:30 AM", "L-2:00 PM", "L-7:00 PM"],

    }),

    created() {
        this.initialize();
    },

    methods: {
        initialize() {
            const fillable = this.fillable
            for (let index in fillable) {
                this.editedItem[fillable[index].field] = fillable[index].value
                this.defaultItem[fillable[index].field] = fillable[index].value
            }
            this.getDateToday();
        },

        editItem(item) {
            this.editedIndex = this.contents.indexOf(item)
            for (let index in this.editedItem) {
                this.editedItem[index] = this.contents[this.editedIndex][index]
            }
            // this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.contents.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true

        },

        deleteItemConfirm() {
            this.$emit('destroyModel', this.editedItem);
            this.contents.splice(this.editedIndex, 1)
            this.closeDelete();
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.contents[this.editedIndex], this.editedItem)
            } else {
                // this.contents.push(this.editedItem)
                this.$emit('storeModel', this.editedItem)
            }
            this.close()
        },

        cancel() {

        },
        deletedSnack() {

        },
        open() {

        },

        displayBets(date) {
            this.$refs.dialog.save(date);
            this.$emit('displayModel', date);
        },

        getDateToday() {
            let date = new Date();
            const month = date.toLocaleString('default', {month: 'numeric'});
            date = date.getFullYear() + "-" + month + "-" + date.getDate();
            this.maxDate = date;
            this.date = date;
        },

        changeAddress(field, address) {
            this.editedItem[field] = address.join(', ');
            this.$emit('changeAddress', address);
        },

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
            if( item.status === "OPEN"){
                this.$emit("storeCloseCombination", item);
            } else if (item.status === "CLOSED"){
                this.$emit("destroyCloseCombination", item);
            }

        }


    },

    computed: {
        formTitle() {
            let title = this.title;
            return this.editedIndex === -1 ? 'Add New ' + title : 'Edit ' + title
        },
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
        menu(val) {
            val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
        },
        contents: function (val) {
            // watch it
            this.contents = val;
        },
    },
};
</script>

<style scoped>
.container.cstm-vuetify-table {
    padding: unset;
}

h3 {
    position: relative;
    text-align: center;
    text-transform: uppercase;
    font-size: 24px;
    font-weight: 500;
    border-bottom: 1px solid #ddd;
    padding-bottom: 20px;
    /*width: fit-content;*/
    /*margin: 0 auto 10px auto;*/
}
h2 {
    font-weight: 600;
    border: unset;
}
h2.cstm-price {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

</style>
<style>
.singleBetTable .myTr td.text-start {
    padding: 10px 16px !important;
}
.singleBetTable .myTr .v-messages.theme--light {
    display: none;
}
.singleBetTable .myTr td>div.v-input--switch {
    margin: unset;
}
.singleBetTable .myDanger td,
.singleBetTable .myWarning td{
    color: #ffffff;
}
</style>

