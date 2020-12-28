<template>
  <v-container>
    <div v-if="notifications.length > 0" v-for="notification in notifications">
      <Notification :notification="notification"></Notification>
    </div>
    <div class="row">
      <div class="col-9 ">
<!--        <h5 class="text-center">{{ this.game }} Gross Total</h5><h4 class="text-center">-->
<!--        $1000</h4>-->
<!--        <v-data-table-->
<!--            :headers="headers"-->
<!--            :items="contents"-->
<!--            :items-per-page="50"-->
<!--            class="elevation-5 p-2 mr-2 ml-2"-->
<!--            hide-default-footer-->
<!--            @page-count="pageCount = $event">-->

<!--          <template v-slot:item.isClosed="{ item }">-->
<!--            <v-switch-->
<!--                v-model="item.isClosed"-->
<!--                inset-->
<!--            ></v-switch>-->
<!--          </template>-->
<!--        </v-data-table>-->
        <BetsDatatable
            :title="game"
            :total="total"
            :headers="headers"
            :contents="contents"
            :fillable="fillable"
            @displayModel="displayBets($event)"
            :canAdd="canAdd"
            :canEdit="canEdit"
            :canDelete="canDelete"
            :hasTopHeader="hasTopHeader"
        />

      </div>

      <div class="col-3">
        <div>
          <v-expansion-panels
              v-model="panel"
              :readonly="readonly"
              class="pt-5"
              multiple>

            <!-- DEFAULT CONFIGURATION -->
            <DefaultConfiguration
                :game="game"
                :defConfigHeaders="defConfigHeaders"
                :defConfigContents="defConfigContents"
                @updateDefaultConfig="updateDefaultConfig($event)"
            />

            <DaysAvailability
                :game="game"
                :daysConfigContents="daysConfigContents"
                v-if="rendered"
                @updateDaysAvailability="updateDaysAvailability($event)"
            />

            <!-- CONTROL COMBINATIONS -->
            <ControlCombinations
                :game="game"
                :controlNumberHeaders="controlNumberHeaders"
                :controlNumberData="controlNumberData"
                @updateControlCombination="updateControlCombination($event)"
                @storeControlCombination="storeControlCombination($event)"
                @destroyControlCombination="destroyControlCombination($event)"
            />

          </v-expansion-panels>
        </div>
      </div>

    </div>
  </v-container>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import DefaultConfiguration from "./DefaultConfiguration";
import DaysAvailability from "./DaysAvailability";
import ControlCombinations from "./ControlCombinations";
import Notification from "../Notification";
import BetsDatatable from "../../components/BetsDatatable";

export default {
  name: "Configuration",
  props: ['game'],
  components: {
    DefaultConfiguration,
    DaysAvailability,
    ControlCombinations,
    Notification,
    BetsDatatable
  },
  data: () => ({
    panel: [0, 0],
    readonly: false,
    daysConfigContents: [],


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

    page: 1,
    pageCount: 0,
    itemsPerPage: 10,
    tabs: '',
    rendered: false,

    savingDefaultConfig: false,
    savingControledNumber: false,
    savingDaysConfig: false,

    // Bets
    total: 5000,
    canAdd: false,
    canEdit: true,
    canDelete: false,
    hasTopHeader: false,

    // Default Configurations
    defConfigHeaders: [
      {text: 'Name', align: 'start', sortable: false, value: 'name',},
      {text: 'Value', value: 'config'},
    ],
    defConfigContents: [],

    // Control Combinations Variables
    controlNumberHeaders: [
      {text: 'Combi', align: 'start', sortable: false, value: 'combination',},
      {text: 'Max Amount', value: 'max_amount'},
      {text: 'Actions', value: 'control_number_actions', sortable: false},
    ],
    controlNumberData: [],
    editedItem: {},
    editedIndex: -1,

    // Notification
    notifications: [],

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

  methods: {
    async index() {
      const response = await axios.get(`/api/v1/games/config/${this.game}`)
          .then(response => {
            const data = response.data.games;
            let count = 0;
            this.contents = [];
            this.controlNumberData = [];
            this.daysConfigContents = [];
            for (let item in data) {
              count++;
              for (let betItem in data[item].bets) {
                let bet = {
                  id: data[item].id,
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
                col_name: "min_per_bet",
                name: "MIN BET AMOUNT",
                config: data[item].game_configuration.min_per_bet
              });
              this.defConfigContents.push({
                col_name: "multiplier",
                name: "MULTIPLIER",
                config: data[item].game_configuration.multiplier
              });
              this.defConfigContents.push({
                col_name: "transaction_limit",
                name: "SEC NOT DUPLICATE",
                config: data[item].game_configuration.transaction_limit
              });
              this.defConfigContents.push({
                col_name: "max_per_bet",
                name: "MAX AMOUNT PER BET",
                config: data[item].game_configuration.max_per_bet
              });
              this.defConfigContents.push({
                col_name: "max_sum_bet",
                name: "MAX HARD BET",
                config: data[item].game_configuration.max_sum_bet
              });
            }
            this.rendered = true;
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

    // DEFAULT CONFIGURATIONS
    async updateDefaultConfig(item) {
      // this.addNotification(item.col_name + " <> " + item.name + " <> " + item.config, "success", 200);
      console.log(item)
      axios.put('/api/v1/games/config/default/' + this.game, item).then(response => {
        console.log(response)
      }).catch(err => {
        console.log(err)
      })
    },

    // DAYS AVAILABILITY
    async updateDaysAvailability(item){
      axios.put('/api/v1/games/config/days/'+this.game, {
        'days':item
      }).then(response => {
        console.log(response)
        this.addNotification("updateControlCombination", "success", 200);
      }).catch(err => {

      })
    },

    // CONTROL COMBINATIONS
    async updateControlCombination(item) {
      axios.put('/api/v1/games/control-combination/'+this.game, {
        'id': item.id,
        'combination': item.combination,
        'max_amount': item.max_amount
      }).then(response => {
        console.log(response)
        this.addNotification("updateControlCombination", "success", 200);
      }).catch(err => {

      })
    },
    async storeControlCombination(item) {
      axios.post('/api/v1/games/control-combination/'+this.game, {
        'combination': item.combination,
        'max_amount': item.max_amount
      }).then(response => {
        console.log(response)
        this.addNotification("storeControlCombination", "success", 200);
      }).catch(err => {
        console.log(err)
      })
    },
    async destroyControlCombination(item) {
      axios.delete('/api/v1/games/control-combination/'+item.id).then(response => {

        this.addNotification("destroyControlCombination", "success", 200);
      }).catch(err => {
        console.log(err)
      })
    },

    // NOTIFICATION
    addNotification(message, type, statusCode) {
      this.notifications.push({message: message, type: type, statusCode: statusCode});
    }
  }
}
</script>

<style scoped>
</style>
