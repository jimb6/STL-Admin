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
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <div class="flex">
                                    <v-btn color="blue" class="text-white mr-2" v-bind="attrs" v-on="on" v-if="canAdd">
                                        <v-icon small class="mr-2">
                                            mdi-plus
                                        </v-icon>
                                        Add New {{ title }}
                                    </v-btn>
                                    <Excel
                                        class="mr-2"
                                        :excelHeaders="excelHeaders"
                                        :excelData="excelData"
                                        :excelTitle="excelTitle"
                                    />
                                    <Pdf
                                        :excelHeaders="excelHeaders"
                                        :excelData="excelData"
                                        :excelTitle="excelTitle"
                                    />
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
                                                    v-if="item.type === 'group-input'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label"
                                                    @input="insertHiddenVal(item.field, editedItem[item.field])"
                                                />

                                                <v-text-field
                                                    v-if="item.type === 'input-phone'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label"
                                                    prefix="+63"
                                                />

                                                <v-text-field
                                                    v-if="item.type === 'input-disabled'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label"
                                                    disabled
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
                                                    item-text="name"
                                                    item-value="id"
                                                    :label="item.label"
                                                    return-object/>

                                                <v-select
                                                    v-if="item.type === 'select-disabled'"
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

                                                <v-select
                                                    v-if="item.type === 'chips-single'"
                                                    v-model="editedItem[item.field]"
                                                    :items="item.options"
                                                    item-text="name"
                                                    :label="item.label"
                                                    attach
                                                    chips
                                                    multiple/>

                                                <v-menu
                                                    v-if="item.type === 'timepicker'"
                                                    ref="menu"
                                                    v-model="menuTime[index]"
                                                    :close-on-content-click="false"
                                                    :nudge-right="40"
                                                    transition="scale-transition"
                                                    offset-y
                                                    max-width="290px"
                                                    min-width="290px">
                                                    <template v-slot:activator="{ on, attrs }">
                                                        <v-text-field
                                                            v-model="editedItem[item.field]"
                                                            :label="item.label"
                                                            readonly
                                                            v-bind="attrs"
                                                            v-on="on"
                                                        />
                                                    </template>
                                                    <v-time-picker
                                                        v-if="menuTime[index]"
                                                        v-model="editedItem[item.field]"
                                                        full-width
                                                        ampm-in-title
                                                        @click:hour="editedItem[item.field]"
                                                        @click:minute="editedItem[item.field]"
                                                        @update:period="editedItem[item.field]"
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
                                                            append-outer-icon="mdi-calendar"
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
                                                    @changeAddress="changeAddress(item.field, $event)"/>
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

                <!-- DIALOG DELETE -->
                <v-dialog v-model="dialogDelete" max-width="500px" v-if="withPassword === true">
                    <v-card>
                        <v-card-title class="headline">Delete this {{ title.toLowerCase() }}?</v-card-title>
                        <v-card-actions class="pt-5">
                            <v-row>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="confirmDeletePassword"
                                        label="Enter Password"
                                        type="password"
                                    />
                                </v-col>
                                <v-col cols="12" align="right">
                                    <v-btn outlined color="blue" @click="closeDelete">
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue" class="text-white" @click="deleteItemConfirm">
                                        Yes
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <v-dialog v-model="dialogDelete" max-width="500px" v-else>
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

            <template v-slot:item.draw_time="{ item }">
                {{ new Date('1/1/2021 ' + item.draw_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") }}
            </template>
            <template v-slot:item.open_time="{ item }">
                {{ new Date('1/1/2021 ' + item.open_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") }}
            </template>
            <template v-slot:item.close_time="{ item }">
                {{ new Date('1/1/2021 ' + item.close_time).toLocaleTimeString().replace(/([\d]+:[\d]{2})(:[\d]{2})(.*)/, "$1$3") }}
            </template>

            <template v-slot:item.roles="{ item }">
                <v-chip v-for="role in item.roles" :key="role.name" dark small class="mr-2">
                    {{ role }}
                </v-chip>
            </template>

            <template v-slot:item.games="{ item }">
                <v-chip class="ma-2" v-for="game in item.games" :key="game.description" dark small>
                    {{ game }}
                </v-chip>
            </template>

            <template v-slot:item.commissions="{ item }">
                <v-chip class="mr-2 my-2" v-for="(commission, index) in item.commissions" :key="index" style="font-weight: 200;" dark small>
                    <p><span class="mr-2">{{ commission.label }}</span><b style="font-weight: 700;">{{ commission.value }}%</b></p>
                </v-chip>
            </template>

            <template v-slot:item.days_availability="{ item }">
                <v-chip class="ma-1" v-for="day in item.days_availability" :key="day" dark small>
                    {{ day }}
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
                    color="primary"
                    inset
                ></v-switch>
            </template>

            <template v-slot:item.isClosed="{ item }">
                <v-switch
                    v-model="item.isClosed"
                    @change="updateStatus(item)"
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
import Excel from "./Excel";
import Pdf from "./Pdf";

export default {
    name: 'DataTable',
    components: {
        Address,
        Excel,
        Pdf
    },
    props: {
        title: String,
        headers: Array,
        contents: Array,
        fillable: Array,
        canAdd: Boolean,
        canEdit: Boolean,
        canDelete: Boolean,

        excelHeaders: Array,
        excelData: Array,
        excelTitle: String,

        dynamicFillable: String,
        withPassword: Boolean,
        loadingRequest: String,
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
        confirmDeletePassword: '',

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',

        birthdate: null,
        menuDate: false,

        time: [],
        menuTime: [],

        hiddenVals: {},

    }),

    created() {
        this.initialize();
    },

    methods: {
        initialize() {
            for (let index in this.fillable) {
                this.editedItem[this.fillable[index].field] = this.fillable[index].value
                this.defaultItem[this.fillable[index].field] = this.fillable[index].value
                this.menuTime[index] = false
            }
        },

        editItem(item) {
            this.initialize();
            this.editedIndex = this.contents.indexOf(item)
            for (let index in this.editedItem) {
                this.editedItem[index] = this.contents[this.editedIndex][index]
            }
            this.editedItem = Object.assign({}, item)
            // we need tp emit the user of the edited item
            if (this.dynamicFillable) {
                this.$emit("setFillable", this.editedItem[this.dynamicFillable]);
            }

            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.contents.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true

        },

        deleteItemConfirm() {
            if( this.withPassword === true ) {
                this.editedItem['confirmDeletePassword'] = this.confirmDeletePassword;
            }
            this.$emit('destroyModel', this.editedItem);
            // this.contents.splice(this.editedIndex, 1)
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
            if (Object.keys(this.hiddenVals).length !== 0) {
                this.editedItem["commission_vals"] = this.hiddenVals;
                this.hiddenVals = {};
            }

            if (this.editedIndex > -1) {
                // EDIT
                // Object.assign(this.contents[this.editedIndex], this.editedItem)
                try {
                    let id = this.contents[this.editedIndex].id;
                    this.editedItem["id"] = id;
                } catch (e) {
                    console.log(e)
                }

                console.log(this.contents[this.editedIndex].id, "ID NI HA")
                this.$emit('updateModel', this.editedItem)
            } else {
                // SAVE
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

        updateStatus(item){
            this.editedIndex = this.contents.indexOf(item);
            item['index'] = this.editedIndex;
            this.$emit('updateStatus', item);
        },

        changeAddress(field, address) {
            this.editedItem[field] = address.join(', ');
            this.$emit('changeAddress', address);
        },

        insertHiddenVal(key, value) {
            this.hiddenVals[key] = value;
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
    },
};
</script>
