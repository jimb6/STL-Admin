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
            class=""
            @page-count="pageCount = $event"
            loading
            loading-text="Loading... Please wait">

            <template v-slot:top>
                <div class="flex-between cstm-table-options my-4 cstm-row col2">
                    <div>
                        <v-dialog v-model="dialog" max-width="500px" v-if="canAdd">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="blue" class="text-white" v-bind="attrs" v-on="on">
                                    <v-icon small class="mr-2">
                                        mdi-plus
                                    </v-icon>
                                    Add New {{ title }}
                                </v-btn>
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
                                                    :label="item.label">
                                                </v-text-field>

                                                <v-select
                                                    v-if="item.type === 'select'"
                                                    v-model="editedItem[item.field]"
                                                    :items="item.options"
                                                    item-text="name"
                                                    item-value="id"
                                                    :label="item.label"
                                                    return-object/>

                                                <v-select
                                                    v-if="item.type === 'chips'"
                                                    v-model="editedItem[item.field]"
                                                    :items="item.options"
                                                    item-text="name"
                                                    item-value="id"
                                                    :label="item.label"
                                                    attach
                                                    chips
                                                    multiple
                                                    return-object/>

                                                <v-menu
                                                    v-if="item.type === 'timepicker'"
                                                    ref="menu"
                                                    v-model="menuTime"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            v-model="editedItem[item.field]"
                                                            label="Draw Time"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        />
                                                    </template>
                                                    <v-time-picker
                                                        v-if="menuTime"
                                                        v-model="time"
                                                        full-width
                                                        ampm-in-title
                                                        @click:hour="editedItem[item.field] = time"
                                                        @click:minute="editedItem[item.field] = time"
                                                        @update:period="editedItem[item.field] = time"
                                                    />
                                                </v-menu>

                                                <v-menu
                                                    v-if="item.type === 'datepicker'"
                                                    ref="menu"
                                                    v-model="menuDate"
                                                    :close-on-content-click="false"
                                                    transition="scale-transition"
                                                    offset-y
                                                    min-width="290px">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            v-model="editedItem[item.field]"
                                                            label="Birthdate"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"></v-text-field>
                                                    </template>
                                                    <v-date-picker
                                                        ref="picker"
                                                        v-model="editedItem[item.field]"
                                                        :max="new Date().toISOString().substr(0, 10)"
                                                        min="1950-01-01"
                                                    ></v-date-picker>
                                                </v-menu>

                                                <Address
                                                    v-model="editedItem[item.field]"
                                                    v-if="item.type === 'address'"
                                                    @changeAddress="changeAddress(item.field, $event)" />
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn outlined color="blue" @click="close">
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue" class="text-white" @click="save">
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </div>
                    <v-text-field
                        v-model="search"
                        append-icon="mdi-magnify"
                        label="Search"
                        single-line
                        hide-details
                    ></v-text-field>
                </div>

                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="headline">Delete this {{ title.toLowerCase() }}?</v-card-title>
                        <v-card-actions class="pt-5">
                            <v-spacer></v-spacer>
                            <v-btn outlined color="blue" @click="closeDelete">
                                Cancel
                            </v-btn>
                            <v-btn color="blue" class="text-white" @click="deleteItemConfirm">
                                Yes
                            </v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)" v-if="canEdit">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)" v-if="canDelete">
                    mdi-delete
                </v-icon>
            </template>

            <template v-slot:item.roles ="{ item }">
                <v-chip  v-for="role in item.roles" :key="role.name" dark close small>
                    {{ role }}
                </v-chip>
            </template>

            <template v-slot:item.games ="{ item }">
                <v-chip  v-for="game in item.games" :key="game.description" dark close small>
                    {{ game }}
                </v-chip>
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

        <v-snackbar v-model="snack" :timeout="3000" :color="snackColor">
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn v-bind="attrs" text @click="snack = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>


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
    name: 'DataTable',
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
