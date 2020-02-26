<template>
    <div class="d-flex flex-column">
        <!--<label :class="'caption ' + (inputProps.error ? 'red&#45;&#45;text':'')">{{  }}</label>-->
        <v-autocomplete
                v-model="selectedItems"
                :items="items"
                dense
                chips
                small
                small-chips
                :label="inputProps.label"
                :error="inputProps.error"
                :error-messages="inputProps['error-messages']"
                :multiple="inputProps.multiple"
        ></v-autocomplete>
    </div>
</template>

<script>
    export default {
        name: "lpd-form-select",
        props: {
            inputProps: Object,
            group: String,
            initValue: Array|String|Object,
        },

        computed: {
            items: function() {
                const objects = this.inputProps.objects;
                const items = [];
                const decorator = this.decorator;

                objects.forEach((o) => {
                    items.push({
                        value: o[decorator.id],
                        text: this.getLabel(o),
                    })
                });

                return items;
            },

            decorator: function() {
                return {
                    id: this.inputProps.decorator ? this.inputProps.decorator.id:'id',
                    title: this.inputProps.decorator ? this.inputProps.decorator.title:'title',
                    label: this.inputProps.decorator ? this.inputProps.decorator.label:':id#:title',
                }
            },

            selectedItems: {
                get() {
                    if (this.selections) {
                        return this.selections;
                    }

                    return this.initValue;
                },
                set(x) {
                    this.selections = x;
                },
            }
        },

        data: () => ({
            selections: null,
        }),

        watch: {
            selectedItems: function(n) {
                const selItems = [];
                const decorator = this.decorator;
                n.forEach((id) => {
                    selItems.push({
                        [decorator.id]: id,
                    });
                });
                this.$emit('input', selItems);
            },
        },

        methods: {
            getLabel: function(item) {
                const decorator = this.decorator;
                return decorator.label.replace(':id', item[decorator.id]).replace(':title', item[decorator.title]);
            },
        }
    }
</script>

<style scoped>

</style>