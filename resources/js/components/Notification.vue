<template>
    <div class="text-center">
        <v-snackbar
            v-model="display"
            :color="snackbar.color"
            :multi-line="snackbar.mode === 'multi-line'"
            :timeout="snackbar.timeout"
            :top="snackbar.position === 'top'"
            right
            app
        >
            <v-layout align-center pr-4>
                <v-icon class="pr-3" dark large>{{ snackbar.icon }}</v-icon>
                <v-layout column>
                    <div>
                        <strong>{{ snackbar.title }}</strong>
                    </div>
                    <div>{{ snackbar.text }}</div>
                </v-layout>
            </v-layout>
            <v-btn v-if="snackbar.timeout === 0" icon @click="snackbar.visible = false">
                <v-icon>clear</v-icon>
            </v-btn>
        </v-snackbar>
    </div>
</template>

<script>
export default {
    name: "Notification",
    props: {
        text: String,
        type: String,
    },
    data: () => ({
        display: true,
        multiLine: true,
        timeout: 3000,
        snackbar: Object,
    }),

    mounted() {
        this.showSnackbar()
    },

    watch: {
        snackbar: ()=>{
             this.showSnackbar()
        }
    },


    methods: {
        showSnackbar(){
            switch (this.type) {
                case "error":
                    this.snackbar = {
                        color: "error",
                        icon: "error",
                        mode: "multi-line",
                        position: "bottom",
                        timeout: this.timeout,
                        title: "Error",
                        text: this.text,
                        visible: true
                    };
                    break;
                case "info":
                    this.snackbar = {
                        color: "info",
                        icon: "info",
                        mode: "multi-line",
                        position: "bottom",
                        timeout: this.timeout,
                        title: "Information",
                        text: this.text,
                        visible: true
                    };
                    break;
                case "success":
                    this.snackbar = {
                        color: "success",
                        // icon: "check_circle",
                        mode: "multi-line",
                        position: "bottom",
                        timeout: this.timeout,
                        title: "Success",
                        text: this.text,
                        visible: true
                    };
                    break;
                case "warning":
                    this.snackbar = {
                        color: "warning",
                        icon: "warning",
                        mode: "multi-line",
                        position: "bottom",
                        timeout: this.timeout,
                        title: "Warning",
                        text: this.text,
                        visible: true
                    };
                    break;
            }
        }
    },

}
</script>

<style scoped>

</style>
