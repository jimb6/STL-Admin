<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ title }}</h2>

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

            <!-- V-SLOTS -->
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
                    :disabled="item.status === 'SOLD OUT'"
                    v-model="item.isClosed"
                    color="white"
                    @change="updateCloseCombination(item)"
                    :loading="loadingRequest"
                ></v-switch>
            </template>

            <template v-slot:body.prepend="{headers}">
                <tr style="background: #4caf50; color: #fff; letter-spacing: 1px;">
                    <td v-for="(header,i) in headers" :key="i">
                        <div v-if="header.value === 'amount'">
                            <b>{{ sumField('amount') }}</b>
                        </div>

                        <div v-if="header.value === 'combinations'" style="text-transform: uppercase">
                            Grand Total
                        </div>

                        <div v-else>
                            <!-- empty table cells for columns that don't need a sum -->
                        </div>
                    </td>
                </tr>
            </template>

        </v-data-table>


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
        },
        sumField(key) {
            // sum data in give key (property)
            return this.contents.reduce((a, b) => a + (b[key] || 0), 0)
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
/*.container.cstm-vuetify-table {*/
/*    padding: unset;*/
/*}*/

/*h3 {*/
/*    position: relative;*/
/*    text-align: center;*/
/*    text-transform: uppercase;*/
/*    font-size: 24px;*/
/*    font-weight: 500;*/
/*    border-bottom: 1px solid #ddd;*/
/*    padding-bottom: 20px;*/
/*    !*width: fit-content;*!*/
/*    !*margin: 0 auto 10px auto;*!*/
/*}*/
/*h2 {*/
/*    font-weight: 600;*/
/*    border: unset;*/
/*}*/
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
.singleBetTable .myWarning td,
.singleBetTable .myInfo td,
.singleBetTable .mySuccess td{
    color: #ffffff;
}
</style>

