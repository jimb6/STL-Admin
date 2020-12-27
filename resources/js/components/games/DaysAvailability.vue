<template>
    <v-expansion-panel class="ml-5 elevation-3 mr-2">
        <v-expansion-panel-header>{{ this.game }} Days Availability
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <template class="p-2">
                <v-chip class="mr-1 my-1" close
                        v-for="day in daysConfigContents"
                        :key="day" dark small>
                    {{ day }}
                </v-chip>
                <v-dialog
                    v-model="dialog"
                    max-width="500px">
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn color="blue" class="mr-1 my-1 text-white" x-small v-bind="attrs" v-on="on" @click="initialize">
                            <v-icon dark small>
                                mdi-plus
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-card>
                        <v-card-title>
                            <span class="headline">Configure Day Availability</span>
                        </v-card-title>

                        <v-card-text>
                            <v-container>
                                <v-row>
                                    <v-col col="12">
                                        <v-select
                                            v-model="editedItem"
                                            :items="days"
                                            attach
                                            chips
                                            label="Days"
                                            multiple
                                            class="cstm-select-small"
                                        />
                                    </v-col>
                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">
                                Cancel
                            </v-btn>
                            <v-btn color="blue darken-1" text @click="save">
                                Save
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </template>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
    export default {
        name: "DaysAvailability",
        props: {
            game: String,
            daysConfigContents: Array,
        },
        data: () => ({
            dialog: false,
            dialogDelete: false,
            editedItem: Array,
            days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
        }),
        created() {
            this.initialize();
        },
        methods: {
            async initialize() {
                this.editedItem = this.daysConfigContents;
                console.log( this.editedItem, "EDITED ITEM" );
            },
            editItem(item) {
                this.dialog = true
            },

            deleteItem(item) {
                this.dialogDelete = true
            },

            deleteItemConfirm() {
                this.closeDelete()
            },

            close() {
                this.dialog = false
            },

            closeDelete() {
                this.dialogDelete = false
            },

            save() {
                console.log( this.editedItem );
                this.close()
            },
        },
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
    }
</script>

<style scoped>
button.v-btn {
    padding: 12px !important;
}
</style>
<style>
.cstm-select-small .v-menu__content.menuable__content__active {
    height: 175px !important;
}
</style>
