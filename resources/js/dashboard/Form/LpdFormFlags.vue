<template>
    <div class="d-flex flex-column">
        <label :class="inputProps.error ? 'red--text':''">{{ inputProps.label }}</label>
        <div class="row">
            <v-checkbox
                    class="col col-lg-2 col-sm-4 col-md-3"
                    v-for="item in inputProps.objects"
                    :key="item[decorator.id]"
                    :value="checkbox[item[decorator.id]]"
                    :label="getLabel(item)"
                    @change="updateInput(item)"
            ></v-checkbox>
        </div>
    </div>
</template>

<script>
    export default {
        name: "lpd-form-flags",
        props: {
            inputProps: Object,
            group: String,
            initValue: {
                type: Number,
                default: 0,
            },
        },
        computed: {
            decorator: function() {
                return {
                    id: this.inputProps.decorator ? this.inputProps.decorator.id:'id',
                    title: this.inputProps.decorator ? this.inputProps.decorator.title:'title',
                    label: this.inputProps.decorator ? this.inputProps.decorator.label:':id#:title',
                }
            },
        },
        data: () => ({
            checkbox: {},
        }),
        mounted() {
            const decorator = this.decorator;
            this.inputProps.objects.forEach((o) => {
                this.checkbox[o[decorator.id]] = (o[decorator.id] & this.initValue) !== 0;
            });

            // console.log(this.initValue, this.checkbox);
        },
        methods: {
            getLabel: function(item) {
                const decorator = this.decorator;
                return decorator.label.replace(':id', item[decorator.id])
                                        .replace(':title', item[decorator.title]);
            },
            updateInput: function(item) {
                let val = 0;
                for (let id in this.checkbox) {
                    if (this.checkbox.hasOwnProperty(id)) {
                        if (this.checkbox[id]) {
                            val += parseInt(id);
                        }
                    }
                }

                this.$emit('input', val);
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