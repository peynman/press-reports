<template>
    <v-expand-transition>
        <div v-show="showForm">
            <v-card flat>
                <lpd-crud-form
                        :id="'table-form-' + formId"
                        v-bind="formProps"
                        @new-tab="newTab"
                />
            </v-card>
            <v-divider></v-divider>
        </div>
    </v-expand-transition>
</template>

<script>
    export default {
        name: "lpd-crud-table-form",
        props: {
            formId: String,
            showForm: Boolean,
            formProps: Object,
        },

        methods: {
            newTab(link, data) {
                this.$emit('new-tab', link, data);
            }
        }
    }
</script>

<style scoped>

</style>