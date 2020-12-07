<template>
    <div>
        <div class="py-3 pt-4 flex-between">
            <div class="cstm-table-filter">
                <div class="flex">
                    <button class="cstm-btn small mr-3" @click="filterDate">Filter</button>
                    <input type="text" class="datepicker mr-3" :class="{'changed': date_changed}" placeholder="Date"
                           :value="date_filter" @change="changeDate($event)">
                    <div class="input-field s12 my-0" v-for="selectItem in selectFilters">
                        <select class="cstm-select">
                            <option value="" disabled selected>{{ selectItem.optionTitle }}</option>
                            <option v-for="(option, index) in selectItem.options"
                                    :value="selectItem.optionValues[index]"
                                    :selected="option == selectItem.optionSelected">{{ option }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="cstm-table_filter" class="dataTables_filter flex-end cstm-search">
                <button class="cstm-btn small icon font-large mr-3" @click="exportToExcel('cstm-table')"><i class="fas fa-file-excel"></i></button>
                <p>Search</p>
                <input type="text" name="search" id="cstm-search" class="cstm-input">
            </div>
        </div>
        <div class="cstm-display">
            <table id="cstm-table" class="table table-bordered">
                <thead>
                <tr>
                    <th v-for="label in labels">{{ label }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="contents.length > 0" v-for="content in contents">
                    <td v-if="withIcon">
                        <span class="avatar"><i :class="iconClass"></i></span>
                    </td>
                    <td v-for="item in content">{{ item }}</td>
                </tr>
                <tr v-if="contents.length == 0" class="odd">
                    <td valign="top" colspan="5" class="dataTables_empty">No matching records found</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="pt-3 text-right">
            <p><b>4/4</b> agents are on duty</p>
        </div>


    </div>
</template>

<script>
import dataTables from "../assets/datatables.min.js";
import materialize from "../assets/materialize.min.js";

export default {
    name: "Table",
    props: {
        labels: Array,
        contents: Array,
        withIcon: Boolean,
        iconClass: String,
        date: String,
        selectFilters: Array
    },
    created() {
        this.date_filter = this.date;
    },
    data() {
        return {
            date_filter: '',
            date_changed: false,
            display_contents: [],
            count: 0,
        }
    },
    methods: {
        filterDate() {
            this.display_contents = [];
            this.date_changed = false;
            this.$emit('filter-by-date', this.date_filter);
        },
        changeDate(event) {
            this.date_changed = (this.date != event.target.value);
            this.date_filter = event.target.value;
        },
        exportToExcel(tableID, filename = '') {
            var downloadurl;
            var dataFileType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTMLData = tableSelect.outerHTML.replace(/ /g, '%20');

            // Specify file name
            filename = filename ? filename + '.xls' : 'export_excel_data.xls';

            // Create download link element
            downloadurl = document.createElement("a");

            document.body.appendChild(downloadurl);

            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTMLData], {
                    type: dataFileType
                });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                // Create a link to the file
                downloadurl.href = 'data:' + dataFileType + ', ' + tableHTMLData;

                // Setting the file name
                downloadurl.download = filename;

                //triggering the function
                downloadurl.click();
            }
        }

    },
    mounted() {
        materialize.AutoInit();
        try {
            const table = $('#cstm-table');
            table.dataTable({
                "bPaginate": false,
                "bInfo": false
            });
            let oTable = table.DataTable();
            $('#cstm-search').keyup(function () {
                oTable.search($(this).val()).draw();
            });
        } catch (e) {
        }
    },
    computed: {
        displayContents() {
            const index = this.count.length - 2;
            const c = [];
            c.splice(0, 1, this.contents);
            console.log(c.splice(0, 1, this.contents));
            return c[0];
        }
    }
};
</script>

<style scoped>
.changed {
    background: #E63946;
    color: #ffffff;
    padding: 6px;
}
</style>
