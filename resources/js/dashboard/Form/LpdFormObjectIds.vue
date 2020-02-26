<template>
    <div class="d-flex flex-column mb-2">
        <label :class="'caption ' + (inputProps.error ? 'red--text':'')">{{ inputProps.label }}</label>
        <div class="row">
            <v-checkbox
                    class="col col-lg-2 col-sm-4 col-md-3"
                    v-for="item in inputProps.objects"
                    v-model="checkbox[item[decorator.id]]"
                    :key="item[decorator.id]"
                    :label="getItemLabel(item)"
                    @change="updateInput(item)"
            ></v-checkbox>
        </div>
        <div class="d-flex flex-column" v-if="inputProps.error">
            <div v-for="err in inputProps['error-messages']" class="red--text">
                {{ err }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "lpd-form-object-ids",
        props: {
            inputProps: Object,
            group: String,
            initValue: Array,
        },
        computed: {
            decorator: function() {
                return {
                    id: this.inputProps.decorator ? this.inputProps.decorator.id:'id',
                    title: this.inputProps.decorator ? this.inputProps.decorator.title:'title',
                    label: this.inputProps.decorator ? this.inputProps.decorator.label: ':id#:title'
                }
            },
        },
        data: () => ({
            checkbox: {},
        }),
        mounted() {
            const decorator = this.decorator;
            if (this.initValue) {
                this.initValue.forEach((o) => {
                    this.checkbox[o[decorator.id]] = true;
                })
            }
        },
        methods: {
            getItemLabel: function(item) {
                const decorator = this.decorator;
                return decorator.label.replace(':id', item[decorator.id]).replace(':title', item[decorator.title]);
            },
            updateInput: function(item) {
                const items = [];
                for (let id in this.checkbox) {
                    if (this.checkbox.hasOwnProperty(id) && this.checkbox[id]) {
                        items.push({
                            id: id,
                        });
                    }
                }
                this.$emit('input', items);
            }
        }
    }
</script>

<style scoped>
    .row {
        margin: 4px !important;
        padding: 2px !important;
    }
    .row .v-input {
        padding: 0px !important;
        margin: 0px !important;
        height: 24px !important;
    }

</style>