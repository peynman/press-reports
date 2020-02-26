<template>
    <div class="text-center">
        <v-menu open-on-hover bottom offset-y>
            <template v-slot:activator="{ on }">
                <v-btn
                        text
                        tile
                        dense
                        small
                        v-on="on"
                >
                    #{{ item[column] ? item[column].length:0 }}
                </v-btn>
            </template>

            <v-list dense
                    class="small-list elevation-0"
                    v-if="item[column] && item[column].length > 0"
            >
                <v-list-item
                        v-for="(li, index) in item[column]"
                        :key="index"
                >
                    <v-list-item-title>{{ getDecoratorLabel(decorator, li) }}</v-list-item-title>
                    <v-divider></v-divider>
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>

<script>
    export default {
        name: "lpd-crud-table-column-hover-list",
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
    .small-list .v-list-item {
        height: 22px;
        min-height: 22px;
    }

    .small-list.v-list {
        padding: 2px !important;
    }
</style>