<template>
    <v-container class="cstm-vuetify-table">
        <h3>{{ title }} Gross TOTAL</h3>
        <h2>${{ total }}</h2>


        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :search="search"
            hide-default-footer
            :sort-desc="[true]"
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
                <div class="flex-between cstm-row col2" v-if="!hasTopHeader">
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

            <template v-slot:item.isClose="{ item }">
                <v-switch
                    v-model="item.isClose"
                    inset
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
    components:{
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
    },

    data: () => ({
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 10,

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
        deletedSnack(){

        },
        open() {

        },

        displayBets(date){
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

        changeAddress(field, address){
            this.editedItem[field] = address.join(', ');
            this.$emit('changeAddress', address);
        },
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
        menu (val) {
            val && setTimeout(() => (this.$refs.picker.activePicker = 'YEAR'))
        },
    },
};
</script>

<style scoped>
.container.cstm-vuetify-table {
    padding: unset;
}
h3 {
    text-align: center;
    text-transform: uppercase;
    font-size: 22px;
    font-weight: 300;
}
h2 {
    font-weight: 600;
}
</style>

