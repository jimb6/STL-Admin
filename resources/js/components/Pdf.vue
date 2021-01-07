<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="red"
                    class="text-white"
                    @click="downloadExcel()"
                    v-bind="attrs"
                    v-on="on">
                    <v-icon small>mdi-download</v-icon>
                </v-btn>
            </template>
            <span>Download {{ this.excelTitle }} pdf file</span>
        </v-tooltip>

        <table ref="table" id="loremTable" v-show="false">
            <thead>
            <tr :style="trStyle">
                <th v-for="header in headers" :style="headerStyle" :colspan="header.colspan">{{ header.name }}</th>
            </tr>
            <tr :style="trStyle">
                <th v-for="subheader in subheaders" :style="subheaderStyle">{{ subheader }}</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in items[0]" :style="trStyle">
                <td v-for="field in fields[0]" :style="tdStyle">{{ item[field] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import jsPDF from 'jspdf'
import autoTable from 'jspdf-autotable'

export default {
    name: "Pdf",
    props: {
        excelHeaders: Array,
        excelData: Array,
        excelTitle: String,
    },
    created() {
    },
    data() {
        return {
            uri: 'data:application/vnd.ms-excel;base64,',
            // data:text/csv;charset=UTF-8,
            template: '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
            base64: function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format: function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            },
            headerStyle: {
                'background-color': 'gray',
                color: '#ffffff',
                padding: '5px 10px',
                'text-align': 'center',
                height: "50px",
            },
            subheaderStyle: {
                'background-color': '#f3f3f3',
                padding: '10px',
                'text-align': 'center',
            },
            trStyle: {
                height: "30px",
            },
            tdStyle: {
                'text-align': 'left',
            },
            headers: [],
            subheaders: [],
            items: [],
            fields: [],

            table: 'table',
        }
    },

    watch: {

        excelHeaders: function (val, oldVal) {
            this.setTableHeader()
            console.log(val, " Headers")
        },

        excelData: function (val, oldVal) {
            this.setExcelData()
            console.log(val, " Data")
        },

    },
    methods: {
        setExcelData() {
            this.items = [];
            this.fields = [];
            this.items.push(this.excelData[0].items);
            this.fields.push(this.excelData[0].fields);
        },
        setTableHeader() {
            this.headers = []
            this.subheaders = []
            for (let i in this.excelHeaders) {
                this.headers.push({name: this.excelHeaders[i].name, colspan: this.excelHeaders[i].subheader.length});
                if (this.excelHeaders[i].subheader.length > 0) {
                    for (let j in this.excelHeaders[i].subheader) {
                        this.subheaders.push(this.excelHeaders[i].subheader[j]);
                    }
                } else {
                    this.subheaders.push("");
                }
            }
        },
        downloadExcel() {
            const doc = new jsPDF()
            doc.text("LIST OF " + this.excelTitle.toUpperCase(), 15, 20)
            autoTable(doc, {
                margin: { top: 30 },
                theme: 'grid',
                headStyles: {
                    halign: 'center',
                    valign: 'middle'
                },
                html: '#loremTable' })
            doc.save(this.excelTitle + '.pdf')
        }
    }
}
</script>

<style scoped>
.table-bordered {
    border:1px solid black
}
td{
    border:1px solid black
}
</style>
