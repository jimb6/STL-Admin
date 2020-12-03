<template>
    <div>
        <div class="cstm-display">
            <table id="cstm-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th v-for="label in labels">{{ label }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="content in contents">
                        <td v-if="withIcon"><span class="avatar"><i :class="content.iconClass"></i></span></td>
                        <td v-for="(item, index) in content" v-if="index != 'iconClass'">{{ item }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="cstm-table-filter flex-between">
            <div>
                <input type="text" class="datepicker mr-4" placeholder="Date">
                <button class="cstm-btn small ml-1">Filter</button>
            </div>
            <div>
                <p><b>4/4</b> agents are on duty</p>
            </div>
        </div>
    </div>
</template>

<script>
    import dataTables from "../assets/datatables.min.js";
    import materialize from "../assets/materialize.min.js";

    export default {
        name: "Table",
        props: {
            labels: {
                type: Array
            },
            contents: {
                type: Array
            },
            withIcon: false
        },
        methods: {

        },
        mounted() {
            materialize.AutoInit();
            $('#cstm-table').dataTable({
                "bPaginate": false,
                "bInfo": false
            });
            var oTable = $('#cstm-table').DataTable();
            $('#cstm-search').keyup(function () {
                console.log("asd");
                oTable.search($(this).val()).draw();
            });
        },
    };
</script>

<style scoped>


</style>
