<template>
    <v-scroll-x-reverse-transition>
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
                        <v-btn @click="display = false" :color="notification.type" depressed>
                            <v-icon small>
                                mdi-close
                            </v-icon>
                        </v-btn>
                    </div>
                </v-col>
            </v-row>
        </v-alert>
    </v-scroll-x-reverse-transition>
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
}
</script>

<style scoped>

</style>
