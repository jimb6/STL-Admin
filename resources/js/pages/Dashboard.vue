<template>

    <div class="dashboard">
        <h1>Dashboard</h1>
        <Table></Table>
    </div>
</template>

<script>
import Table from "../components/Table";
export default {
    name: "Dashboard",
    components: {
        Table,
    },
    data() {
        return {
            card : [
                {
                    id: 1,
                    title: "Agents",
                    active: 25,
                    overall: 100
                },
                {
                    id: 2,
                    title: "Booths",
                    active: 25,
                    overall: 100
                },
            ]
        };
    },
};
</script>

<style scoped>

</style>
