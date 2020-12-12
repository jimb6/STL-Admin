<template>
    <v-container class="cstm-vuetify-table">
        <h2>{{ tableName }} Table</h2>

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

        <v-data-table
            :headers="headers"
            :items="contents"
            :page.sync="page"
            :items-per-page="itemsPerPage"
            :search="search"
            hide-default-footer
            class="elevation-1"
            @page-count="pageCount = $event"
            multi-sort
            loading
            loading-text="Loading... Please wait"
        >

            <template v-slot:top>
                <v-dialog v-model="dialogDelete" max-width="500px">
                    <v-card>
                        <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                            <v-spacer></v-spacer>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>


            <template v-slot:item.age="props">
                <v-edit-dialog
                    :return-value.sync="props.item.age"
                    large
                    persistent
                    @save="save"
                    @cancel="cancel"
                    @open="open"
                    @close="close"
                >
                    <div>{{ props.item.age }}</div>
                    <template v-slot:input>
                        <div class="mt-4 title">
                            Update Age
                        </div>
                        <v-text-field
                            v-model="props.item.age"
                            :rules="[max25chars]"
                            label="Edit"
                            single-line
                            counter
                            autofocus
                        ></v-text-field>
                    </template>
                </v-edit-dialog>
            </template>

            <template v-slot:item.actions="{ item }">
                <v-icon small @click="deleteItem(item)">
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>

        <v-snackbar
            v-model="snack"
            :timeout="3000"
            :color="snackColor"
        >
            {{ snackText }}

            <template v-slot:action="{ attrs }">
                <v-btn
                    v-bind="attrs"
                    text
                    @click="snack = false"
                >
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
                ></v-pagination>
            </div>
        </div>

<!--        <div class="cstm-card2-cont">-->
<!--            <div class="card2" v-for="n in 4">-->
<!--                <h3>John Doe</h3>-->
<!--                <div class="overlay">-->
<!--                    <h2 class="title">John Doe</h2>-->
<!--                    <a class="link" href="#">Edit</a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
    </v-container>

</template>

<script>
export default {
    name: 'DataTable',
    props: {
        tableName: String,
        headers: Array,
        contents: Array,
        defaultItem: Array,
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

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',

    }),
    created() {
        this.editedItem = this.defaultItem;
    },
    methods: {
        // deleteItem(item) {
        //     const index = this.contents.indexOf((x) => x.id === item.id);
        //     this.contents.splice(index, 1);
        // },
        editItem(item) {
            this.editedIndex = this.contents.indexOf(item)
            this.editedItem = Object.assign({}, item)
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
                this.editedItem = this.defaultItem
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = this.defaultItem
                this.editedIndex = -1
            })
        },

        save() {
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
