<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ tableName.substring(0, tableName.length - 1) }} Cards</h2>

        <div class="flex-between cstm-table-options my-4 cstm-row col2">
            <div>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="blue" class="text-white" v-bind="attrs" v-on="on">
                            <v-icon small class="mr-2">
                                mdi-plus
                            </v-icon>
                            Add New {{ tableName.substring(0, tableName.length - 1) }}
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
                                            return-object
                                        />

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
                                                    v-on="on"
                                                ></v-text-field>
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
                <v-card-title class="headline">Delete this {{ tableName.substring(0, tableName.length - 1).toLowerCase() }}?</v-card-title>
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



        <v-data-iterator
            class="cstm-card2-cont"
            :items="contents"
            item-key="name"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :search="search"
            @page-count="pageCount = $event"
            hide-default-footer>

            <template v-slot:default="{ items }">
                <div class="card2" v-for="(item, i) in items">
                    <div class="flex-between cstm-card2-header">
                        <div>
                            <h4 class="p-3">{{ item.name }}</h4>
                        </div>
                        <v-avatar :color="getRandomColor()" class="white--text " size="30">
                            {{ itemInitials(item.name) }}
                        </v-avatar>
                    </div>

                    <v-list dense>
                        <v-list-item v-for="(value, index) in item" :key="index" v-if="index != 'count' && index != 'name'">
                            <v-list-item-content v-for="(header, i) in headers" v-if="header.value === index" :key="i">
                                <span>{{header.text}}</span> <p>{{ value }}</p>
                            </v-list-item-content>
                        </v-list-item>
                    </v-list>
                    <div class="overlay">
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn outlined color="white" @click="editItem(item)">
                                <v-icon small class="mr-2">
                                    mdi-pencil
                                </v-icon>
                                Edit
                            </v-btn>
                            <v-btn outlined color="white" @click="deleteItem(item)">
                                <v-icon small class="mr-2">
                                    mdi-delete
                                </v-icon>
                                Delete
                            </v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </div>
                </div>
            </template>
        </v-data-iterator>

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
    name: "Card2",
    components:{
        Address
    },
    props: {
        tableName: String,
        headers: Array,
        contents: Array,
        fillable: Array,
    },

    data: () => ({
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 9,

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
            // this.editedItem = Object.assign({}, item)
            this.dialogDelete = true

        },

        deleteItemConfirm() {
            this.contents.splice(this.editedIndex, 1)
            this.$emit('destroyUser', this.editedItem)
            this.closeDelete()
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
                this.snackText = 'Item updated!'
            } else {
                // this.contents.push(this.editedItem)
                this.$emit('storeUser', this.editedItem)
                this.snackText = 'Item saved!'
            }
            this.snack = true
            this.snackColor = 'success'

            this.close()
        },
        cancel() {
            this.snack = true
            this.snackColor = 'error'
            this.snackText = 'Canceled'
        },
        open() {
            this.snack = true
            this.snackColor = 'info'
            this.snackText = 'Update Item'
        },
        changeAddress(field, address){
            this.editedItem[field] = address.join(', ');
            this.$emit('changeAddress', address);
        },

        itemInitials(name){
            let initials = name.match(/\b\w/g) || [];
            initials = ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
            return initials;
        },
        getRandomColor(){
            return '#' + Math.floor(Math.random()*16777215).toString(16);
        },
    },

    computed: {
        formTitle() {
            let title = this.tableName.substring(0, this.tableName.length - 1);
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
    },
}
</script>

<style scoped>
/* --- Card2 ---- */
.cstm-card2-header {
    border-bottom: 1px solid #ddd;
}
.cstm-card2-header h4 {
    margin: unset;
}
.card2 {
    position: relative;
    display: inline-block;
    min-width: 300px;
    min-height: 300px;
    margin: 1em;
    background-size: cover;
    border-radius: 10px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    transition: 0.5s;
    padding: 15px;
    background-color: #fff;
}

.card2 .overlay {
    width: 100%;
    height: fit-content;
    position: absolute;
    padding: 1em;
    bottom: 0;
    left: 0;
    z-index: 10;
    color: #fff;
    -moz-transition: 0.3s;
    -o-transition: 0.3s;
    -webkit-transition: 0.3s;
    transition: 0.3s;
    text-align: center;
}

.card2 .overlay>div {
    position: relative;
    width: 100%;
    top: 200px;
    text-decoration: none;
    color: #FFFFFF;
    -moz-transition: 0.5s;
    -o-transition: 0.5s;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    padding-top: 0;
    padding-bottom: 0;
}

.card2:hover .overlay {
    background: #2196f3;
}

.card2:hover h2 {
    top: 0px;
}

.card2:hover .overlay>div {
    top: 0px;
}

@media screen and (max-width: 700px) {
    /* --- Card2 ---- */
    .card2 {
        position: relative;
        display: block;
        width: 100%;
        height: 300px;
        margin: 3em 0em;
        border-radius: 0px;
        box-shadow: 0px 25px 50px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        transition: all .4s ease;
    }
}

.cstm-card2-cont {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
}
.v-list-item__content {
    display: grid;
    grid-template-columns: 100px 1fr;
    font-size: 14px;
    flex: unset;
    flex-wrap: unset;
    margin: unset !important;
    align-items: unset;
    width: 100%;
}
.v-list-item__content span {
    color: #ccc;
    font-weight: 300;
}


</style>
