<template>
    <v-navigation-drawer
            absolute
            temporary
            v-model="visible"
            :right="!rtl"
    >
        <v-toolbar dense dark flat color="light-blue darken-2" >
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon small v-if="visible" @click.stop="setOptionsMenuVisible(false)">
                <v-icon>close</v-icon>
            </v-btn>
        </v-toolbar>
        <v-list
                dense
        >
            <lpd-crud-form ref="form"
                           v-bind="optionsProps"
                           @on-submit="onSubmit"
                           @on-success="onSuccess"
            />
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { mapMutations, mapState } from 'vuex'
    import mutations from '../Lib/mutations'
    import LpdCrudForm from "../Form/LpdCRUDForm";

    export default {
        components: {LpdCrudForm},
        name: "lpd-options-menu",
        computed: {
            optionsProps() {
                const datetime_formats = this.datetimeFormats;

                return {
                    id: 'app-options',
                    mode: 'simple',
                    columns: 1,
                    dense: true,
                    small: true,
                    onBeforeSubmit: function(form) {
                        return {
                            session: 'options',
                            options: form.data,
                        }
                    },
                    initValue: this.currentValues,
                    fields: [
                        {
                            id: 'calendar',
                            label: this.translations.forms.labels.calendar,
                            input: 'select',
                            objects: [
                                {
                                    id: 'gregorian',
                                    title: this.translations.forms.objects.calendars.gregorian,
                                },
                                {
                                    id: 'shamsi',
                                    title: this.translations.forms.objects.calendars.shamsi,
                                },
                                {
                                    id: 'ghamari',
                                    title: this.translations.forms.objects.calendars.ghamari
                                },
                            ],
                            multiple: false,
                            decorator: {
                                id: 'id',
                                title: 'title',
                                label: ':title',
                            },
                        },
                        {
                            id: 'timezone',
                            label: this.translations.forms.labels.timezone,
                            input: 'select',
                            objects: [],
                            multiple: false,
                            decorator: {
                                id: 'id',
                                title: 'title',
                                label: ':title',
                            },
                        },
                        {
                            id: 'created_at_format',
                            label: this.translations.forms.labels.created_at_format,
                            input: 'select',
                            objects: datetime_formats,
                            multiple: false,
                            decorator: {
                                id: 'id',
                                title: 'title',
                                label: ':title',
                            },
                        },
                        {
                            id: 'updated_at_format',
                            label: this.translations.forms.labels.updated_at_format,
                            input: 'select',
                            objects: datetime_formats,
                            multiple: false,
                            decorator: {
                                id: 'id',
                                title: 'title',
                                label: ':title',
                            },
                        },
                        {
                            id: 'language',
                            label: this.translations.forms.labels.language,
                            input: 'select',
                            objects: this.languages,
                            multiple: false,
                            decorator: {
                                id: 'id',
                                title: 'title',
                                label: ':title',
                            },
                        },
                    ],
                    actions: [
                        {
                            name: 'submit',
                            label: this.translations.forms.buttons.options_submit,
                            action: this.updateUrl,
                            class: 'ma-0 pa-0 mt-2',
                            props: {
                                block: true,
                                color: 'primary',
                                dense: true,
                                small: true,
                            },
                        },
                        {
                            name: 'apply',
                            label: this.translations.forms.buttons.options_apply_only,
                            class: 'ma-0 pa-0 mt-1',
                            props: {
                                block: true,
                                outlined: true,
                                color: 'success',
                                dense: true,
                                small: true,
                            },
                        },
                        {
                            name: 'reset',
                            label: this.translations.forms.buttons.options_reset,
                            class: 'ma-0 pa-0 mt-1',
                            props: {
                                block: true,
                                outlined: true,
                                color: 'warning',
                                dense: true,
                                small: true,
                            },
                        },
                    ],
                }
            },
            ...mapState({
                drawerIcon: state => state.config.rtl ? 'chevron_right':'chevron_left',
                rtl: state => state.config.rtl,
                title: state => state.page.options.title,
                updateUrl: state => state.page.options.update.url,
                translations: state => state.config.language.translations,
                languages: state => state.config.language.available,
                defaultValues: state => state.page.options.defaults,
                currentValues: function(state) {
                    if (this.currValues) {
                        return this.currValues;
                    }

                    return state.page.options.values;
                },
                filterValues: state => state.page.options.filters.options ? state.page.options.filters.options.options:null,
                datetimeFormats: function(state) {
                    const datetime_formats = [];
                    state.config.datetime.modes.forEach((mode) => {
                        datetime_formats.push({
                            id: mode,
                            title: mode,
                        })
                    });
                    return datetime_formats;
                },
            }),
            visible: {
                get: function() {
                    return this.$store.state.page.options.visible;
                },
                set: function(v) {
                    this.setOptionsMenuVisible(v);
                },
            }
        },

        data: () => ({
            currValues: null,
        }),
        methods: {
            onSubmit(action) {
                if (action.button) {
                    switch (action.button.name) {
                        case 'reset':
                            this.currValues = {...this.defaultValues};
                            this.setOptions(this.currValues);
                            break;
                        case 'apply':
                            this.setOptions(action.data);
                            break;

                    }
                }
            },
            onSuccess(response) {
                this.setOptions(response.data.options);
            },
            ...mapMutations([
                mutations.setOptionsMenuVisible,
                mutations.setOptions,
                mutations.setLanguage,
            ]),
        },

        created() {
            if (this.filterValues) {
                this.currValues = this.filterValues;
                this.setOptions(this.currValues);
            }
        },
    }
</script>

<style scoped>

</style>