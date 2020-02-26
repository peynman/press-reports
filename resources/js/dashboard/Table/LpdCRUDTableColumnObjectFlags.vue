<template>
    <div class="d-flex flex-row text-center justify-center align-center">
        <v-tooltip top
                   v-for="(icon,index) in icons"
                   :key="'icon-'+index"
        >
            <template v-slot:activator="{ on }">
                <v-icon
                        v-on="on"
                        :color="icon.color"
                        small
                >
                    {{ icon.icon }}
                </v-icon>
            </template>
            <span>{{ icon.title }}</span>
        </v-tooltip>
    </div>
</template>

<script>
    export default {
        name: "lpd-crud-table-column-object-flags",

        props: {
            item: Object,
            params: Object,
            column: String,
        },

        computed: {
            icons: function() {
                const value = this.item[this.column];
                const icons = [];
                const decorator = this.decorator;
                const objects = this.params.objects;

                objects.forEach((o) => {
                    if ((o[decorator.id] & value) !== 0) {
                        const nIcon = {
                            title: this.getDecoratorLabel(decorator, o),
                        };
                        if (this.params.icons && this.params.icons[o[decorator.id]]) {
                            nIcon.icon = this.params.icons[o[decorator.id]];
                        }
                        if (this.params.colors && this.params.colors[o[decorator.id]]) {
                            nIcon.color = this.params.colors[o[decorator.id]];
                        }
                        icons.push(nIcon);
                    }
                });

                return icons;
            },

            decorator: function() {
                return {
                    id: this.params.decorator ? this.params.decorator.id:'id',
                    title: this.params.decorator ? this.params.decorator.title:'title',
                    label: this.params.decorator ? this.params.decorator.label:':id#:title',
                }
            },
        },
    }
</script>

<style scoped>

</style>