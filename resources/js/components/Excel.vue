<template>
    <div>
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="green"
                    class="text-white py-1"
                    @click="downloadExcel()"
                    v-bind="attrs"
                    v-on="on"
                    style="border-radius: 5px"
                >
                    <v-icon size="20">mdi-file-excel</v-icon>
                </v-btn>
            </template>
            <span>Download {{ this.excelTitle }} Excel file</span>
        </v-tooltip>
        <table ref="table" id="loremTable" v-show="false" :style="tableStyle">
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

export default {
    name: "Excel",
    props: {
        excelHeaders: Array,
        excelData: Array,
        excelTitle: String,
        reportsUrl: String,
    },
    created() {
    },
    data() {
        return {
            uri: 'data:application/vnd.ms-excel;base64,',
            // data:text/csv;charset=UTF-8,
            template: '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
                'xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>' +
                '<x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions>' +
                '</x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]-->' +
                '</head><body><table>{table}</table></body></html>',
            base64: function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            },
            format: function (s, c) {
                return s.replace(/{(\w+)}/g, function (m, p) {
                    return c[p];
                })
            },
            tableStyle: {
                width: '100%',
                'border-collapse': 'separate',
                'border-spacing': '0 50px',
            },
            headerStyle: {
                'background-color': '#1ABD9C',
                color: '#ffffff',
                'text-align': 'center',
                height: "50px",
            },
            subheaderStyle: {
                'background-color': '#f1f1f1',
                'padding-top': '10px',
                'padding-right': '10px',
                'padding-bottom': '10px',
                'padding-left': '10px',
                'text-align': 'center',
            },
            trStyle: {
                'text-align': 'center',
            },
            tdStyle: {
                'text-align': 'left',
                'padding-top': '10px',
                'padding-right': '20px',
                'padding-bottom': '10px',
                'padding-left': '20px',
                'line-height': '30px',
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

            console.log('EXCEL: ', this.reportsUrl)
            const url = this.reportsUrl
            const link = document.createElement('a')
            link.href = url
            link.click()

            // let form = document.createElement("form");
            // form.setAttribute("method", "post");
            // form.setAttribute("action", url);
            // document.body.appendChild(form);
            // form.submit();

            // let a = document.createElement('a');
            // let table = this.table;
            // let name = this.excelTitle;
            // if (!table.nodeType) table = this.$refs.table
            // let ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            // a.href = this.uri + this.base64(this.format(this.template, ctx))
            // a.download = this.excelTitle + '.xls';
            // a.click();

            // let table = this.table;
            // let name = this.excelTitle;
            // if (!table.nodeType) table = this.$refs.table
            // var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            // window.location.href = this.uri + this.base64(this.format(this.template, ctx))
        }
    }
}
</script>

<style scoped>
</style>
