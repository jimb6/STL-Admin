<template>
    <div>
        <DataTable
            :contents="contents"
            :headers="headers" />
    </div>
</template>

<script>
import DataTable from "../components/DataTable";
import Vue from "vue";
import Vuetify from 'vuetify'
Vue.use(Vuetify)

export default {
    name: "User",
    props: {
        userData: String,
    },
    components: {
        DataTable,
    },

    data: () => ({
        headers: [
            {text: "User ID", value: "userId"},
            {text: "Name", value: "id"},
            {text: "Email", value: "title"},
            {text: "User ID", value: "userId"},
            {text: "ID", value: "id"},
            {text: "Title", value: "title"},
            {text: "User ID", value: "userId"},
            {text: "ID", value: "id"},
            {text: "Title", value: "title"},
            {text: "Actions", value: "actions", sortable: false},
        ],
        contents: [],
    }),
    created() {
        const data = JSON.parse(this.userData)['data'];
        for( let content in data){
            this.contents.push(data[content]);
        }
        console.log( this.contents );
    }
}
</script>

<style scoped>

</style>
