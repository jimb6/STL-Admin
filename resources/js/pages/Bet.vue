<template>
    <v-card>
        <v-toolbar
            flat
            color="primary"
            dark
        >
            <v-toolbar-title>Bet Transactions</v-toolbar-title>
        </v-toolbar>
        <v-tabs vertical>
            <v-tab v-for="(bet, index) in games">
                <v-icon left>
                    mdi-gamepad-variant-outline
                </v-icon>
                {{ bet.game_name }}
            </v-tab>
            <v-tab-item>
                <v-card flat>
                    <v-data-table
                        dense
                        :headers="labels"
                        :items="contents"
                        item-key="name"
                        class="elevation-1"
                    ></v-data-table>
                </v-card>
            </v-tab-item>
        </v-tabs>
    </v-card>
</template>

<script>
import Table from '../components/DataTable';

export default {
    name: "Bet",
    components: {
        Table
    },
    props: {
        betType: String,
    },
    data() {
        return {
            desserts: [
                {
                    name: 'Frozen Yogurt',
                    calories: 159,
                    fat: 6.0,
                    carbs: 24,
                    protein: 4.0,
                    iron: '1%',
                },
                {
                    name: 'Ice cream sandwich',
                    calories: 237,
                    fat: 9.0,
                    carbs: 37,
                    protein: 4.3,
                    iron: '1%',
                },
                {
                    name: 'Eclair',
                    calories: 262,
                    fat: 16.0,
                    carbs: 23,
                    protein: 6.0,
                    iron: '7%',
                },
                {
                    name: 'Cupcake',
                    calories: 305,
                    fat: 3.7,
                    carbs: 67,
                    protein: 4.3,
                    iron: '8%',
                },
                {
                    name: 'Gingerbread',
                    calories: 356,
                    fat: 16.0,
                    carbs: 49,
                    protein: 3.9,
                    iron: '16%',
                },
                {
                    name: 'Jelly bean',
                    calories: 375,
                    fat: 0.0,
                    carbs: 94,
                    protein: 0.0,
                    iron: '0%',
                },
                {
                    name: 'Lollipop',
                    calories: 392,
                    fat: 0.2,
                    carbs: 98,
                    protein: 0,
                    iron: '2%',
                },
                {
                    name: 'Honeycomb',
                    calories: 408,
                    fat: 3.2,
                    carbs: 87,
                    protein: 6.5,
                    iron: '45%',
                },
                {
                    name: 'Donut',
                    calories: 452,
                    fat: 25.0,
                    carbs: 51,
                    protein: 4.9,
                    iron: '22%',
                },
                {
                    name: 'KitKat',
                    calories: 518,
                    fat: 26.0,
                    carbs: 65,
                    protein: 7,
                    iron: '6%',
                },
            ],
            headers: [
                {
                    text: 'Dessert (100g serving)',
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                {text: 'Calories', value: 'calories'},
                {text: 'Fat (g)', value: 'fat'},
                {text: 'Carbs (g)', value: 'carbs'},
                {text: 'Protein (g)', value: 'protein'},
                {text: 'Iron (%)', value: 'iron'},
            ],
            withIcon: true,
            iconClass: 'fas fa-coins',
            date: '',
            selectFilters: [
                {
                    optionTitle: "Draw Period",
                    options: ['10:30AM', '4PM', '9PM'],
                    optionValues: ['1', '2', '3'],
                    optionSelected: '4PM',
                }
            ],
            bets: [],
            games: [],
        }

    }
    ,
    mounted() {
        this.fetchData();
    }
    ,
    methods: {
        getDateToday() {
            const today = new Date();
            const month = today.toLocaleString('default', {month: 'short'});
            const date = month + " " + today.getDate().toString().padStart(2, "0") + ", " + today.getFullYear();
            return date;
        }
        ,
        getContentsByDate(date) {
            this.date = (date == '') ? this.getDateToday() : date;
            return this.contents;
        }
        ,

        async fetchData() {
            const response = await axios.get('/api/bets', {
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json',
                }
            }).catch(err => {
                console.log(err)
            });
            this.bets = response.data.bets
            this.games = response.data.betGames
        }

    }
}
</script>

<style scoped>

</style>
