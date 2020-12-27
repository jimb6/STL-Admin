<template>
    <div class="row">
        <div class="col-3">
            <div>
                <v-expansion-panels
                    v-model="panel"
                    :readonly="readonly"
                    class="pt-5"
                    multiple>

                    <v-expansion-panel class="ml-5 elevation-3 mr-2">
                        <v-expansion-panel-header>{{ this.game }} Default Configurations
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                :headers="defConfigHeaders"
                                :items="defConfigContents"
                                hide-default-footer>
                                <template v-slot:item.config="props">
                                    <v-edit-dialog
                                        :return-value.sync="props.item.config"
                                        large
                                        persistent
                                        @save="saveDefaultConfig"
                                        @cancel="cancelDefaultConfig"
                                        @open="openDefaultConfig"
                                        @close="closeDefaultConfig">
                                        <div>{{ props.item.config }}</div>
                                        <template v-slot:input>
                                            <div class="mt-4 title">
                                                UPDATE {{ props.item.name }}
                                            </div>
                                            <v-text-field
                                                v-model="props.item.config"
                                                label="Edit"
                                                single-line
                                                counter
                                                autofocus
                                            ></v-text-field>
                                        </template>
                                    </v-edit-dialog>
                                </template>

                            </v-data-table>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

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
                            </template>
                        </v-expansion-panel-content>
                    </v-expansion-panel>

                    <v-expansion-panel class="ml-5 elevation-3 mr-2">
                        <v-expansion-panel-header>
                            {{ this.game }} Max Specific Combination
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-data-table
                                :headers="controlNumberHeaders"
                                :items="controlNumberData"
                                hide-default-footer>
                                <template v-slot:top>
                                    <v-toolbar flat>
                                        <v-dialog
                                            v-model="dialog"
                                            max-width="500px">
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="primary"
                                                    dark
                                                    icon
                                                    class="mb-2"
                                                    v-bind="attrs"
                                                    v-on="on">
                                                    <v-icon dark>
                                                        mdi-plus
                                                    </v-icon>
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
                                                            <v-col
                                                                sm="6"
                                                                md="4">
                                                                <v-text-field
                                                                    v-model="editedItem.name"
                                                                    label="Combination">
                                                                </v-text-field>
                                                            </v-col>
                                                            <v-col
                                                                sm="6"
                                                                md="4">
                                                                <v-text-field
                                                                    v-model="editedItem.calories"
                                                                    label="Amount">
                                                                </v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                </v-card-text>

                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="close"
                                                    >
                                                        Cancel
                                                    </v-btn>
                                                    <v-btn
                                                        color="blue darken-1"
                                                        text
                                                        @click="save"
                                                    >
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

                </v-expansion-panels>
            </div>
        </div>


        <div class="col-9 ">
            <h5 class="text-center">{{ this.game }} Gross Total</h5><h4 class="text-center">
            $1000</h4>
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
            <v-data-table
                :headers="headers"
                :items="contents"
                :items-per-page="50"
                class="elevation-5 p-2 mr-2 ml-2"
                hide-default-footer
                @page-count="pageCount = $event">
                <template v-slot:item.isClosed="{ item }">
                    <v-switch
                        v-model="item.isClosed"
                        inset
                    ></v-switch>
                </template>
            </v-data-table>
        </div>

    </div>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'

export default {
    name: "Configuration",
    props: ['game'],
    data: () => ({
        panel: [0, 0],
        readonly: false,

        snack: false,
        snackColor: '',
        snackText: '',
        max25chars: v => v.length <= 25 || 'Input too long!',
        pagination: {},

        editedIndex: -1,

        defConfigHeaders: [
            {
                text: 'Name',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            {text: 'Value', value: 'config'},
        ],
        defConfigContents: [],

        daysConfigContents: [],

        controlNumberHeaders: [
            {
                text: 'Combi',
                align: 'start',
                sortable: false,
                value: 'combination',
            },
            {text: 'Max Amount', value: 'max_amount'},
            {text: 'Actions', value: 'control_number_actions', sortable: false},
        ],
        controlNumberData: [],

        headers: [
            {text: "Combinations", value: "combinations"},
            {text: "₱ Total Amount", value: "amount"},
            {text: "Status", value: "status"},
            {text: "Actions", value: "isClosed"},
        ],
        contents: [],
        fillable: [
            {label: "Description", field: "description", value: "", type: "input"},
            {label: "Abbreviation", field: "abbreviation", value: "", type: "input"},
            {label: "Prize", field: "prize", value: "", type: "input"},
            {label: "Field Set", field: "field_set", value: "", type: "input"},
            {label: "Digit Per Field Set", field: "digit_per_field_set", value: "", type: "input"},
            {label: "Min Number", field: "min_number", value: "", type: "input"},
            {label: "Max Number", field: "max_number", value: "", type: "input"},
            {label: "Has Repetition", field: "has_repetition", value: "", type: "select", options: [true, false]},
            {
                label: "Days Availability",
                field: "days_availability",
                value: "",
                type: "chips",
                options: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
            },
        ],
        editedItem: {},
        notifications: [],

        page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        tabs: '',

        savingDefaultConfig: false,
        savingControledNumber: false,
        savingDaysConfig: false,

        dialog: false,
        dialogDelete: false,

    }),
    created() {
        this.index();
    },

    watch: {
        dialog(val) {
            val || this.close()
        },
        dialogDelete(val) {
            val || this.closeDelete()
        },
    },


    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
    },

    methods: {
        async index() {
            const response = await axios.get(`/api/v1/games/config/${this.game}`)
                .then(response => {
                    const data = response.data.games;
                    let count = 0;
                    this.contents = []
                    for (let item in data) {
                        count++;
                        for (let betItem in data[item].bets) {
                            let bet = {
                                combinations: data[item].bets[betItem].combination,
                                amount: data[item].bets[betItem].amount,
                                status: data[item].bets[betItem].is_closed ? "Closed" : "Open",
                                isClosed: data[item].bets[betItem].is_closed,
                            }
                            this.contents.push(bet)
                        }

                        for (let controlItem in data[item].control_combination) {
                            let contComb = {
                                id: data[item].control_combination[controlItem].id,
                                combination: data[item].control_combination[controlItem].combination,
                                max_amount: data[item].control_combination[controlItem].max_amount,
                            }
                            this.controlNumberData.push(contComb)
                        }

                        this.daysConfigContents = data[item].game_configuration.days_availability
                        this.defConfigContents.push({
                            name: "MIN BET AMOUNT",
                            config: data[item].game_configuration.multiplier
                        });
                        this.defConfigContents.push({
                            name: "MULTIPLIER",
                            config: data[item].game_configuration.multiplier
                        });
                        this.defConfigContents.push({
                            name: "SEC NOT DUPLICATE",
                            config: data[item].game_configuration.transaction_limit
                        });
                        this.defConfigContents.push({
                            name: "MAX AMOUNT PER BET",
                            config: data[item].game_configuration.max_per_bet
                        });
                        this.defConfigContents.push({
                            name: "MAX HARD BET",
                            config: data[item].game_configuration.max_sum_bet
                        });
                    }
                    console.log(response)
                })

                .catch(err => {
                    console.log(response)
                    this.addNotification("Failed to load " + this.title + "s", "error", "400");
                });
        },

        async storeGame(item) {
            const response = await axios.post('/api/games',
                {
                    'description': item.description,
                    'abbreviation': item.abbreviation,
                    'prize': item.prize,
                    'field_set': item.field_set,
                    'digit_per_field_set': item.digit_per_field_set,
                    'min_number': item.min_number,
                    'max_number': item.max_number,
                    'has_repetition': item.has_repetition,
                    'days_availability': item.days_availability,
                })
                .then(response => {
                    this.addNotification(item.description + " added successfully!", "success", "200");
                })
                .catch(err => {
                    this.addNotification(item.description + " unsuccessfully added!", "error", "400");
                });

            await this.displayGames()
        },

        async updateGame() {

        },

        async destroyGame(item) {
            const response = await axios.delete('/api/v1/games/' + item.id)
                .then(response => {
                    this.addNotification(item.description + " deleted successfully!", "success", "200")
                })
                .catch(err => {
                    this.addNotification(item.description + " unsuccessfully deleted!", "error", "400")
                });
            await this.displayGames();
        },

        async storeDefaultConfig(){

        },

        async updateDefaultConfig(){

        },

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
            this.controlNumberData.splice(this.editedIndex, 1)
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
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },


        saveDefaultConfig () {
            this.snack = true
            this.snackColor = 'success'
            this.snackText = 'Data saved'
        },
        cancelDefaultConfig () {
            this.snack = true
            this.snackColor = 'error'
            this.snackText = 'Canceled'
        },
        openDefaultConfig () {
            this.snack = true
            this.snackColor = 'info'
            this.snackText = 'Dialog opened'
        },
        closeDefaultConfig () {
            console.log('Dialog closed')
        },

        addNotification(message, type, statusCode) {
            this.notifications.push({message: message, type: type, statusCode: statusCode});
        }
    }
}
</script>

<style scoped>

</style>
