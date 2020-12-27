<template>
    <div class="row">
        <div class="col-12">
            <h5 class="text-center"></h5>


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
                class="elevation-1"
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
export default {
    name: "WinningCombination",
    data: () => ({
        headers: [
            {text: "#", value: "count"},
            {text: "Combinations", value: "combination"},
            {text: "Draw Period", value: "drawPeriod"},
            {text: "Draw Date", value: "drawDate"},
            {text: "Draw Game", value: "drawGame"},
            {text: "Validity", value: "validity"},
            {text: "Verified", value: "verified"},
            {text: "Actions", value: "isClosed"},
        ],
        contents: [],
        panel: [1, 0],
        defConfigHeaders: [
            {
                text: 'Name',
                align: 'start',
                sortable: false,
                value: 'name',
            },
            {text: 'Value', value: 'config'},
        ],

        defConfigContents:[],
    })
}
</script>

<style scoped>

</style>
