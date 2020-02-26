<template>
    <lpd-crud-form
            :id="'create-form-' + formId"
            v-bind="formProps"
            @new-tab="newTab"
            mode="simple"
    />
</template>

<script>
    export default {
        name: "lpd-crud-create",
        props: {
            metadata: Object,
            initValue: Array|Object,
        },
        computed: {
            formId: function() {
                return this.getRandomString(5);
            },
            formProps: function() {
                let initValue = this.initValue;

                if (initValue && this.metadata.resource.maps) {
                    for (let map in this.metadata.resource.maps) {
                        if (this.metadata.resource.maps.hasOwnProperty(map)) {
                            initValue[this.metadata.resource.maps[map]] = this.getDotStr(initValue, map);
                        }
                    }
                }

                return {
                    fields: this.metadata.form.create.fields,
                    options: this.metadata.form.create.options,
                    validations: this.metadata.form.create.validations,
                    actions: this.metadata.form.actions,
                    action: this.metadata.resource.urls.create.post,
                    initValue: initValue,
                    width: 12,
                }
            },
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