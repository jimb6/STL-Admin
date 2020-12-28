<template>
    <v-slide-y-transition>
        <v-alert v-if="display"
                 prominent
                 :type=" notification.type "
                 transition="fade-transition">
            <v-row align="center">
                <v-col class="grow">
                    {{ notification.message }}
                </v-col>
                <v-col class="shrink" v-if="notification.type == 'error'">
                    <div>
                        <v-btn color="#ffffff15" depressed>
                            Send Report
                        </v-btn>
                    </div>
                </v-col>
                <v-col class="shrink">
                    <div>
                        <v-btn @click="display = false" :color="notification.type" depressed class="close-notification-btn">
                            <v-icon small>
                                mdi-close
                            </v-icon>
                        </v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-alert>
    </v-slide-y-transition>
</template>

<script>
export default {
    name: "Notification",
    props: {
        notification: Object
    },
    data: () => ({
        display: true,
    }),
    created() {
        this.closeNotification();
    },
    methods: {
        closeNotification(){
            setTimeout(function(){
                $(".close-notification-btn").click();
            }, 3000)


        }
    }
}
</script>

<style scoped>

</style>
