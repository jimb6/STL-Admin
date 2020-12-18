<template>
    <v-scroll-x-reverse-transition>
        <v-alert v-if="display"
                 prominent
                 type="error"
                 transition="fade-transition">
            <v-row align="center">
                <v-col class="grow">
                    {{this.message}}
                </v-col>
                <v-col class="shrink">
                    <div>
                        <v-btn>Send Report</v-btn>
                    </div>
                </v-col>
                <v-col class="shrink">
                    <div>
                        <v-btn @click="closeError">Close</v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-alert>
    </v-scroll-x-reverse-transition>
</template>

<script>
export default {
    name: "ErrorNotif",
    props: ["message", "error"],
    data: () => ({
        display: true,
    }),
    methods: {
        closeError() {
            this.display = false;
        }
    }
}
</script>

<style scoped>

</style>
