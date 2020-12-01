<template>

</template>

<script>
export default {
    name: "Dashboard",
    data() {
        return {
            loading: false,
            agents: null,
            error: null,
        };
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData(page = 1) {

        },
    }
}
</script>

<style scoped>

</style>
