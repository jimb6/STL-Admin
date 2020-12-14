<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ tableName }} Table</h2>


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
            loading-text="Loading... Please wait"
        >

            <template v-slot:top>
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
                                                    v-if="item.type== 'input'"
                                                    v-model="editedItem[item.field]"
                                                    :label="item.label">
                                                </v-text-field>

                                                <v-select
                                                    v-if="item.type== 'select'"
                                                    v-model="editedItem[item.field]"
                                                    :items="item.options"
                                                    :label="item.label" >
                                                </v-select>

                                                <Address
                                                    v-model="editedItem[item.field]"
                                                    v-if="item.type == 'address'"
                                                    @changeAddress="changeAddress(item.field, $event)"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn outlined color="blue" @click="close">
                                        <!--                                        <v-icon small class="mr-2">-->
                                        <!--                                            mdi-cancel-->
                                        <!--                                        </v-icon>-->
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue" class="text-white" @click="save">
                                        <!--                                        <v-icon small class="mr-2">-->
                                        <!--                                            mdi-check-->
                                        <!--                                        </v-icon>-->
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
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)">
                    mdi-pencil
                </v-icon>
                <v-icon small @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
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
        tableName: String,
        headers: Array,
        contents: Array,
        fillable: Array,
    },

    data: () => ({
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 8,

        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: {},
        defaultItem: {},

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',

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
            this.contents.splice(this.editedIndex, 1)
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
            } else {
                this.contents.push(this.editedItem)
            }
            this.snack = true
            this.snackColor = 'success'
            this.snackText = 'Item updated'
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
            this.editedItem[field] = address;
        }
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
};
</script>

<style scoped>
.cstm-card2-cont {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
}

/* --- Card2 ---- */

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
    height: 100%;
    position: absolute;
    padding: 1em;
    top: 0;
    left: 0;
    z-index: 10;
    color: #fff;
    -moz-transition: 0.5s;
    -o-transition: 0.5s;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    text-align: center;
}

.card2 .overlay h2 {
    position: relative;
    margin: 2em 0px;
    top: -200px;
    -moz-transition: 0.5s;
    -o-transition: 0.5s;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}

.card2 .overlay a {
    position: relative;
    width: 60%;
    top: 200px;
    padding: 0.5em 2em;
    border: 2px solid #fff;
    text-decoration: none;
    color: #FFFFFF;
    border-radius: 3px;
    -moz-transition: 0.5s;
    -o-transition: 0.5s;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}

.card2 a:hover {
    background: #fff;
    color: #5c5c5c;
}

.card2:hover .overlay {
    background: rgba(0, 0, 0, 0.8);
}

.card2:hover h2 {
    top: 0px;
}

.card2:hover a {
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
</style>
