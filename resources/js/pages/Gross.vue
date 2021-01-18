<template>
    <v-main>
        <v-container>
            <div class="row">
                <div class="col-12">
                    <v-tabs slot="extension"
                            v-model="tabs"
                            slider-color="primary"
                            color="danger"
                            light
                    >
                        <!-- TAB TITLES -->
                        <v-tab>
                            <v-icon small class="mr-2">mdi-account</v-icon>
                            By Agent
                        </v-tab>
                        <v-tab>
                            <v-icon small class="mr-2">mdi-home</v-icon>
                            By Cluster
                        </v-tab>
                        <v-tab>
                            <v-icon small class="mr-2">mdi-clock</v-icon>
                            By Draw Period
                        </v-tab>
                        <v-tab>
                            <v-icon small class="mr-2">mdi-gamepad</v-icon>
                            By Game
                        </v-tab>
                        <v-tab>
                            <v-icon small class="mr-2">mdi-gamepad-circle-left</v-icon>
                            By Draw Period & Game
                        </v-tab>

                        <!-- TAB ITEMS -->
                        <v-tab-item>
                            <ByAgent />
                        </v-tab-item>

                        <v-tab-item>
                            <ByCluster />
                        </v-tab-item>

                        <v-tab-item>
                            <ByDrawPeriod />
                        </v-tab-item>

                        <v-tab-item>
                            <ByGame />
                        </v-tab-item>

                        <v-tab-item>
                            <ByDrawPeriodGame />
                        </v-tab-item>
                    </v-tabs>
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>

import '@mdi/font/css/materialdesignicons.css'
import ByCluster from "./reports/ByCluster";
import ByAgent from "./reports/ByAgent";
import ByDrawPeriod from "./reports/ByDrawPeriod";
import ByGame from "./reports/ByGame";
import ByDrawPeriodGame from "./reports/ByDrawPeriodGame";

export default {
    name: "Gross",
    components: {
        ByCluster,
        ByAgent,
        ByDrawPeriod,
        ByGame,
        ByDrawPeriodGame
    },
    data: () => ({
        title: "Overall Gross",
        tabs: '',
    }),
    created() {

    },

    computed: {
    },

    methods: {

    }
}
</script>
