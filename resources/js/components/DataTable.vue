<template>

    <div class="cstm-vuetify-table">
        <h2>Users Table</h2>

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
            <template v-slot:item.actions="{ item }">
                <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
            </template>
        </v-data-table>


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
                ></v-pagination>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: 'DataTable',
    props: {
        headers: Array,
        contents: Array,
    },

    data: () => ({
        search: "",
        page: 1,
        pageCount: 0,
        itemsPerPage: 10,
    }),
    methods: {
        deleteItem(item) {
            const index = this.contents.indexOf((x) => x.id === item.id);
            this.contents.splice(index, 1);
        },
    },
};
</script>

<style scoped>
.cstm-vuetify-table {
    font-family: 'Poppins';
    padding: 50px;
}
.cstm-vuetify-table h2 {
    text-align: center;
    text-transform: uppercase;
    font-weight: 300;
    padding-bottom: 10px;
    border-bottom: 1px solid #ddd;
}
.cstm-table-options>div {
    width: 25%;
    display: block !important;
    flex: unset;
    padding: unset;
    margin: unset;
}
</style>
