<template>

    <div class="dashboard">
        <h1>Dashboard</h1>
        <Card />
    </div>

</template>

<script>
import Card from "../components/Card";
export default {
    name: "Dashboard",
    components: {
        Card,
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
