<template>
    <v-text-field
            ref="inputValue"
            v-model="value"
            v-bind="textFieldProps"
            @input="updateInput()"
            :small="inputProps.dense"
    ></v-text-field>
</template>

<script>
    export default {
        name: "lpd-form-text-input",
        props: {
            inputProps: Object,
            group: String,
            initValue: String,
        },

        computed: {
            textFieldProps: function() {
                return {
                    ...this.inputProps,
                    label: this.inputProps.label + (this.inputProps.required ? ' *':''),
                }
            },
        },

        methods: {
            updateInput: function () {
                this.$emit('input', this.value);
            }
        },

        data: () => ({
            value: null,
        }),

        mounted() {
            if (this.initValue) {
                this.value = this.initValue;
            }
        },
    }
</script>

<style scoped>

</style>