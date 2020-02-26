<template>
    <div class="text-center">
        <v-chip
                v-for="(li, index) in item[column]"
                :key="index"
                dense
                small
        >
            {{ getDecoratorLabel(decorator, li) }}
        </v-chip>
    </div>
</template>

<script>
    export default {
        name: "lpd-crud-table-column-objects-list",
        props: {
            item: Object,
            params: Object,
            column: String,
        },
        computed: {
            decorator: function() {
                return {
                    id: this.params.decorator ? this.params.decorator.id:'id',
                    title: this.params.decorator ? this.params.decorator.title:'title',
                    label: this.params.decorator ? this.params.decorator.label:':id#:title',
                }
            },
        },
        data: () => ({

        }),
        methods: {

        },
    }
</script>

<style scoped>
</style>