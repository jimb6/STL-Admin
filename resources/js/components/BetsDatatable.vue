<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ title }}s Table</h2>


        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :search="search"
            hide-default-footer
            :sort-desc="[true]"
            @page-count="pageCount = $event"
            loading
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div>
                    </div>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
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
import Address from "./Address";

export default {
    name: 'BetsDatatable',
    components:{
        Address
    },
    props: {
        title: String,
        headers: Array,
        contents: Array,
        fillable: Array,
        canAdd: Boolean,
        canEdit: Boolean,
        canDelete: Boolean,
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
        menuDate: false,

        time: null,
        menuTime: false,

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

