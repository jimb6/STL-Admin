<template>
    <v-card class="mx-auto" >
        <v-card-text>
            <div>Word of the Day</div>
            <p class="display-1 text--primary">
                el·ee·mos·y·nar·y
            </p>
            <p>adjective</p>
            <div class="text--primary">
                relating to or dependent on charity; charitable.<br>
                "an eleemosynary educational institution."
            </div>
        </v-card-text>
        <v-card-actions>
            <v-btn
                text
                color="teal accent-4"
                @click="reveal = true"
            >
                Learn More
            </v-btn>
        </v-card-actions>

        <v-expand-transition>
            <v-card
                v-if="reveal"
                class="transition-fast-in-fast-out v-card--reveal"
                style="height: 100%;"
            >
                <v-card-text class="pb-0">
                    <p class="display-1 text--primary">
                        Origin
                    </p>
                    <p>late 16th century (as a noun denoting a place where alms were distributed): from medieval Latin eleemosynarius, from late Latin eleemosyna ‘alms’, from Greek eleēmosunē ‘compassion’ </p>
                </v-card-text>
                <v-card-actions class="pt-0">
                    <v-btn
                        text
                        color="teal accent-4"
                        @click="reveal = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-expand-transition>
    </v-card>
</template>

<script>
export default {
    name: "GlobalSettings",
    data: () => ({
        valid: true,
        name: '',
        nameRules: [
            v => !!v || 'Name is required',
            v => (v && v.length <= 10) || 'Name must be less than 10 characters',
        ],
        email: '',
        emailRules: [
            v => !!v || 'E-mail is required',
            v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        ],
        select: null,
        items: [
            'Item 1',
            'Item 2',
            'Item 3',
            'Item 4',
        ],
        checkbox: false,
    }),
    methods: {
        validate () {
            this.$refs.form.validate()
        },
        reset () {
            this.$refs.form.reset()
        },
        resetValidation () {
            this.$refs.form.resetValidation()
        },
    },
}
</script>

<style scoped>
.v-card--reveal {
    bottom: 0;
    opacity: .5 !important;
    position: relative;
    width: 100%;
}
</style>
