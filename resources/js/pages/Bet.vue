<template>
    <div class="cstm-container agents fit-modal">
        <section>
            <div class="cstm-row">
                <div class="flex-center">
                    <h1>{{ betType }}</h1>
                </div>
            </div>
        </section>

        <section>
            <Table
                :labels="labels"
                :contents="getContentsByDate(date)"
                :withIcon="withIcon"
                :iconClass="iconClass"
                :date="date"
                :selectFilters="selectFilters"
                @filter-by-date="getContentsByDate"
            />
        </section>

    </div>
</template>

<script>
import Table from '../components/Table';
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
            labels: ['', 'Heading 1', 'Heading 2', 'Heading 3', 'Heading 4'],
            contents: [
                ['Content 1', 'Content 2', 'Content 3', 'Content 4'],
            ],
            withIcon: true,
            iconClass: 'fas fa-coins',
            date: '',
            selectFilters: [
                {
                    optionTitle: "Draw Period",
                    options: ['10:30AM', '4PM', '9PM'],
                    optionValues: ['1', '2', '3'],
                    optionSelected: '10:30AM',
                }
            ],
        }
    },
    methods: {
        getDateToday(){
            const today = new Date();
            const month = today.toLocaleString('default', { month: 'short' });
            const date = month + " " + today.getDate().toString().padStart(2, "0") + ", " + today.getFullYear();
            return date;
        },
        getContentsByDate(date){
            this.date = (date == '') ? this.getDateToday() : date;
            return this.contents;
        }
    }
}
</script>

<style scoped>

</style>
