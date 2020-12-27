<template>
    <v-card
        :loading="loading"
        class="mx-auto my-12">
        <template slot="progress">
            <v-progress-linear
                color="deep-purple"
                height="10"
                indeterminate
            ></v-progress-linear>
        </template>


        <v-card-text>
            <v-select
                :items="items"
                label="Solo field"
                dense
                class="d-inline-flex pa-2"
                tile
                solo
            ></v-select>
            <v-select
                :items="items"
                label="Solo field"
                dense
                class="d-inline-flex pa-2"
                tile
                solo
            ></v-select>
            <v-select
                :items="items"
                label="Solo field"
                dense
                class="d-inline-flex pa-2"
                tile
                solo
            ></v-select>
        </v-card-text>
        <v-col
            sm="6"
            md="4"
        >
            <v-menu
                v-model="menu2"
                :close-on-content-click="false"
                :nudge-right="40"
                transition="scale-transition"
                offset-y
                min-width="290px"
            >
                <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="date"
                        label="Picker without buttons"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                    ></v-text-field>
                </template>
                <v-date-picker
                    v-model="date"
                    @input="menu2 = false"
                ></v-date-picker>
            </v-menu>
        </v-col>
        <v-spacer></v-spacer>
    </v-card>
</template>

<script>
import Bet from './Bet'
export default {
    name: "Reports",
    date: () => ({
        date: new Date().toISOString().substr(0, 10),
        menu: false,
        modal: false,
        menu2: false,

    })
}
</script>

<style scoped>

</style>
