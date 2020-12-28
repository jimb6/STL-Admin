<template>
    <v-expansion-panel class="ml-5 elevation-3 mr-2">
        <v-expansion-panel-header>{{ this.game }} Default Configurations
        </v-expansion-panel-header>
        <v-expansion-panel-content>
            <v-data-table
                :headers="defConfigHeaders"
                :items="defConfigContents"
                hide-default-footer>
                <template v-slot:item.config="props">
                    <v-edit-dialog
                        :return-value.sync="props.item.config"
                        large
                        @save="save(props.item)"
                        @cancel="cancel"
                        @open="open(props.item)"
                        @close="close"
                    >
                        <div>{{ props.item.config }}</div>
                        <template v-slot:input>
                            <div class="mt-4 title">
                                UPDATE {{ props.item.name }}
                            </div>
                            <v-text-field
                                v-model="props.item.config"
                                label="Edit"
                                single-line
                                counter
                                autofocus
                            ></v-text-field>
                        </template>
                    </v-edit-dialog>
                </template>

            </v-data-table>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
export default {
    name: "DefaultConfiguration",
    props: {
        game: String,
        defConfigHeaders: Array,
        defConfigContents: Array,
    },
    data: () => ({
        editedItem: {},
        temp: '',
        rules: {
            required: value => !!value || 'Required.',
            numeric: 'numeric',
            min: v => v.length >= 8 || 'Min 8 characters',
            emailMatch: () => (`The email and password you entered don't match`),
        },
    }),
    created() {

    },

    methods: {
        save(item) {
            if (item.config === this.editedItem.config){}
            else
            {
                console.log('nausab ko.')
                this.$emit('updateDefaultConfig', item);
            }
        },
        cancel() {
            this.snack = true
            this.snackColor = 'error'
            this.snackText = 'Canceled'
        },
        open(item) {
            this.editedItem.config = item.config
        },
        close() {
            console.log('Dialog closed')
        },
    }
}
</script>

<style scoped>

</style>
