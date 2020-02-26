<template>
    <div class="d-flex flex-row" @click.stop="">
        <v-tooltip top v-for="act in params.actions" :key="act.label">
            <template v-slot:activator="{ on }">
                <v-btn text icon small
                       @click.native.prevent="doAction(act)"
                       v-bind:href="itemHref(act)"
                       v-on="on">
                    <v-icon small>{{ act.icon }}</v-icon>
                </v-btn>
            </template>
            <span>{{  act.label.replace(':id', item.id) }}</span>
        </v-tooltip>
    </div>
</template>

<script>
    export default {
        name: "lpd-crud-table-column-options",
        props: {
            item: Object,
            params: Object,
        },

        computed: {
        },

        methods: {
            itemHref: function(act) {
                return !act.act.href ? act.act.url.replace(':id', this.item.id):null;
            },

            doAction(action, data) {
                console.log(action, data);
                if (action.act.type) {
                    switch (action.act.type) {
                        case 'new-tab':
                            this.$emit('new-tab', {
                                title: action.label.replace(':id', this.item.id),
                                url: action.act.url.replace(':id', this.item.id),
                            }, this.item, data);
                            break;
                    }

                    return false;
                }

                return true;
            },
        },
    }
</script>

<style scoped>

</style>