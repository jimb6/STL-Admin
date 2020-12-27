<template>

    <!-- Max Specific Combination -->
    <v-expansion-panel class="ml-5 elevation-3 mr-2">
        <v-expansion-panel-header>
            {{ this.game }} Max Specific Combination
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <v-data-table
                :headers="controlNumberHeaders"
                :items="controlNumberData"
                hide-default-footer>
                <template v-slot:bottom>

                </template>
                <template v-slot:top>
                    <v-toolbar flat>
                        <v-dialog
                            v-model="dialog"
                            max-width="500px">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn color="blue" class="text-white mb-2 w-100" v-bind="attrs" v-on="on">
                                    <v-icon dark small>
                                        mdi-plus
                                    </v-icon>
                                    Add New
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span
                                        class="headline">{{ formTitle }}
                                    </span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col col="12">
                                                <v-text-field
                                                    v-model="editedItem.combination"
                                                    label="Combination"
                                                />
                                            </v-col>
                                            <v-col col="12">
                                                <v-text-field
                                                    v-model="editedItem.max_amount"
                                                    label="Amount"
                                                />
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close" >
                                        Cancel
                                    </v-btn>
                                    <v-btn color="blue darken-1" text @click="save">
                                        Save
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>

                        <v-dialog v-model="dialogDelete" max-width="400px">
                            <v-card>
                                <v-card-title class="headline">Are you sure you
                                    want to delete this item?
                                </v-card-title>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text
                                           @click="closeDelete">Cancel
                                    </v-btn>
                                    <v-btn color="blue darken-1" text
                                           @click="deleteItemConfirm">OK
                                    </v-btn>
                                    <v-spacer></v-spacer>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.control_number_actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)">
                        mdi-delete
                    </v-icon>
                </template>
            </v-data-table>
        </v-expansion-panel-content>
    </v-expansion-panel>


</template>

<script>
export default {
    name: "ControlCombinations",
    props: {
        game: String,
        controlNumberHeaders: Array,
        controlNumberData: Array,
    },
    data: () => ({
        dialog: false,
        dialogDelete: false,
        editedItem: {},
    }),
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
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
    methods: {
        editItem(item) {
            this.editedIndex = this.controlNumberData.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },

        deleteItem(item) {
            this.editedIndex = this.controlNumberData.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },

        deleteItemConfirm() {
            // this.controlNumberData.splice(this.editedIndex, 1)
            this.$emit('destroyControlCombination', this.editedItem);
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
                // EDIT
                // Object.assign(this.desserts[this.editedIndex], this.editedItem)
                this.$emit('updateControlCombination', this.editedItem);
            } else {
                // INSERT
                // this.controlNumberData.push(this.editedItem)
                this.$emit('storeControlCombination', this.editedItem)
            }
            this.close()
        },
    }
}
</script>

<style scoped>

</style>
