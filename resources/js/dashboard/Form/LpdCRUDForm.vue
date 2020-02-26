<template>
    <v-layout justify-center>
        <v-col cols="12" sm="12" :md="width" class="text-xs-center">
            <v-alert
                    v-model="alert.show"
                    :type="alert.type"
                    transition="slide-y-transition"
                    border="top"
                    dense
                    dismissible
            >
                {{ alert.message }}
            </v-alert>
            <v-form
                    ref="form"
                    v-model="valid"
            >
                <v-row no-gutters class="ma-3">
                    <v-col
                            cols="12"
                            sm="12"
                            :md="columns === 1 ? '12':'9'"
                    >
                        <v-expansion-panels
                                v-model="panels"
                                multiple
                                accordion
                                popout
                                v-if="mode === 'panels'"
                                class="ma-0 pa-0 elevation-0"
                        >
                            <v-expansion-panel
                                    v-for="(group, prop, n) in formGroups"
                                    :key="`${formId}-${prop}-step`"
                                    class="ma-0 pa-0 elevation-0"
                            >

                                <v-expansion-panel-header
                                        class="ma-0 pa-0 elevation-0"
                                >
                                    <template v-slot:default="{ open }">
                                        <div class="d-flex justify-space-between align-center">
                                            <span
                                                    class="col-4 text--secondary"
                                            >
                                                {{group.title}}
                                            </span>
                                            <div class="d-flex justify-end align-center" v-if="! open">

                                            </div>
                                            <div class="d-flex justify-end align-center"
                                                @click.stop=""
                                            >
                                                <div  v-if="(panels.length > 0 && panels.includes(n) && panels.every((v) => v <= n)) || (panels.length === 0 && n === 0)">
                                                    <v-btn text small dense color="warning" @click="resetFormValidations">{{ translations.forms.buttons.validate_reset }}</v-btn>
                                                    <v-btn text small dense color="success" @click="validateAndNext">{{ translations.forms.buttons.validate_n_next }}</v-btn>
                                                    <v-tooltip top v-if="openNewTab">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn v-on="on" text small dense icon
                                                                   color="text--secondary"
                                                                   @click.native.prevent="openFormInNewTab()"
                                                                   :href="openNewTab.url"
                                                            >
                                                                <v-icon small>open_in_new</v-icon>
                                                            </v-btn>
                                                        </template>
                                                        <span>{{ translations.forms.buttons.open_n_tab}}</span>
                                                    </v-tooltip>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <component
                                            v-for="field in group.fields"
                                            :key="formId+'-group-'+group.id+'-field-'+field.id"
                                            :is="field.component"
                                            :form-id="formId"
                                            v-bind="field.props"
                                            v-model="formData[field.id]"
                                            :init-value="initValue ? initValue[field.id]:null"
                                    />
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                        <div v-if="mode === 'simple'">
                            <v-row
                                v-for="(group, prop, n) in formGroups"
                                :key="`${formId}-${prop}-step`"
                                class="simple-row"
                            >
                                <v-col
                                        v-for="field in group.fields"
                                        :key="formId+'-group-'+group.id+'-field-'+field.id"
                                        :class="field.col ? field.col:'col-12'"
                                    >
                                    <component
                                            :is="field.component"
                                            :form-id="formId"
                                            v-bind="field.props"
                                            v-model="formData[field.id]"
                                            :init-value="initValue ? initValue[field.id]:null"
                                    />
                                </v-col>
                            </v-row>
                        </div>
                    </v-col>
                    <v-col
                            cols="12"
                            sm="12"
                            :md="columns === 1 ? '12':'3'"
                            :class="columns === 1 ? '':'ps-md-4'"
                    >
                        <v-btn
                                v-for="button in buttons"
                                :key="formId+'-action-'+button.name"
                                v-bind="button.props"
                                :class="button.class"
                                :loading="loading"
                                @click="submit(button)"
                        >
                            {{ button.label }}
                        </v-btn>
                    </v-col>
                </v-row>
            </v-form>
        </v-col>
    </v-layout>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "lpd-crud-form",
        props: {
            title: String,
            fields: Array,
            options: Object|Array,
            id: String,
            action: String,
            method: String,
            actions: Array,
            validations: Object,
            dense: {
                type: Boolean,
                default: true,
            },
            small: {
                type: Boolean,
                default: true,
            },
            width: {
                type: Number,
                default: 12,
            },
            openNewTab: Object,
            mode: {
                type: String,
                default: 'panels',
            },
            columns: {
                type: Number,
                default: 2,
            },
            onBeforeSubmit: Function,
            initValue: Object,
        },

        computed: {
            formFields: function() {
                const formFields = [];
                const alert = this.alert;

                this.fields.forEach((field) => {
                    const fieldComponent = {
                        id: field.id,
                        component: 'lpd-form-'+field.input,
                        group: field.group,
                        col: field.col,
                        props: {
                            inputProps: {
                                small: this.small,
                                dense: this.dense,
                                ...field,
                            }
                        },
                    };

                    if (this.validations && this.validations[field.id]) {
                        if (this.validations[field.id].includes('required')) {
                            fieldComponent.props.inputProps.required = true;
                        }
                        fieldComponent.props.inputProps.rules = this.getFieldRules(field, this.validations[field.id]);
                    }
                    formFields.push(fieldComponent);
                });

                if (alert && alert.messages) {
                    for (let errField in alert.messages) {
                        if (alert.messages.hasOwnProperty(errField)) {
                            let myFieldId = errField;
                            if (myFieldId.includes('.')) {
                                myFieldId = myFieldId.split('.')[0];
                            }

                            formFields.forEach((field) => {
                                if (field.props.inputProps.id === myFieldId) {
                                    field.props.inputProps.error = true;
                                    field.props.inputProps['error-messages'] = alert.messages[errField];
                                }
                            });
                        }
                    }
                }

                return formFields;
            },
            buttons: function() {
                const buttons = [];

                if (this.actions && this.actions.length > 0) {
                    buttons.push(...this.actions);
                } else {
                    buttons.push({
                        label: this.translations.forms.buttons.submit,
                        name: 'return',
                        class: 'mt-2',
                        props: {
                            color: 'primary',
                            dense: true,
                            block: true,
                        },
                    });
                    buttons.push({
                        label: this.translations.forms.buttons.submit_n_edit,
                        name: 'edit',
                        class: 'mt-2',
                        props: {
                            color: 'primary',
                            text: true,
                            dense: true,
                            small: true,
                            block: true,
                        }
                    });
                    buttons.push({
                        label: this.translations.forms.buttons.submit_n_another,
                        name: 'add-another',
                        class: 'mt-2',
                        props: {
                            color: 'primary',
                            text: true,
                            small: true,
                            block: true,
                        }
                    });
                }

                return buttons;
            },
            alert: function() {
                if (!this.response) {
                    return {
                        show: false,
                    }
                }

                return {
                    show: true,
                    message: this.response.data.message,
                    type: this.response.status !== 200 ? 'error':'success',
                    messages: this.response.data.errors,
                }
            },
            formId: function() {
                return this.id ? this.id: this.getRandomString(10);
            },
            ...mapState({
                formGroups(state) {
                    const fGroups = state.config.language.translations.forms.groups;
                    const fields = this.formFields;
                    const groups = {};

                    let groupCounter = 0;
                    fields.forEach((f) => {
                        if (f.group) {
                            if (!groups[f.group]) {
                                groups[f.group] = {
                                    id: f.group,
                                    title: fGroups[f.group] ? fGroups[f.group].title: f.group,
                                    icon: fGroups[f.group] ? fGroups[f.group].icon: f.group,
                                    fields: [],
                                };

                                groupCounter++;
                            }

                            groups[f.group].fields.push(f);
                        } else {
                            if (!groups['extras']) {
                                groups['extras'] = {
                                    id: 'extras',
                                    title: fGroups['extras'].title,
                                    icon: fGroups['extras'].icon,
                                    fields: [],
                                };

                                groupCounter++;
                            }
                            groups['extras'].fields.push(f);
                        }
                    });

                    this.steps = groupCounter;

                    if (groupCounter === 0) {
                        groups['common'] = {
                            id: 'common',
                            title: '',
                            icon: '',
                            fields: [],
                        };
                        fields.forEach((f) => {
                            groups['common'].fields.push(f);
                        });
                    }

                    return groups;
                },
                translations: state => state.config.language.translations,
            })
        },

        data: () => ({
            valid: true,
            loading: false,
            response: null,
            formData: {},
            step: 'required',
            steps: 1,
            panels: [0],
        }),

        watch: {
            initValue: function(n) {
                console.log('new form data', n);
                this.formData = n; // keep init value in formData if no a no change is submitted
            },
        },

        created() {
            if (this.initValue) {
                this.formData = this.initValue;
            }
        },

        methods: {
            submit(button) {
                const actionUrl = button.action ? button.action : this.action;
                const action = { url: actionUrl, button, data: this.formData };
                this.$emit('on-submit', action);

                if (actionUrl) {
                    this.loading = true;
                    let formData = this.formData;
                    if (this.onBeforeSubmit) {
                        formData = this.onBeforeSubmit(action);
                    }
                    axios.post(actionUrl, formData)
                        .then((response) => {
                            this.response = response;
                            this.$emit('on-success', response);
                        })
                        .catch((error) => {
                            this.response = error.response;
                            this.$emit('on-failed', error.response);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                }
            },
            validateStepFields(group) {
                const groups = this.formGroups;
                const ref = this;

                return function() {
                    let messages = [];
                    let firstErrorGroup = null;
                    if (groups[group] && groups[group].fields) {
                        groups[group].fields.forEach((f) => {
                            if (f.props.inputProps.error) {
                                messages.push(...f.props.inputProps['error-messages']);
                                firstErrorGroup = group;
                            }
                        })
                    }

                    if (firstErrorGroup) {
                        ref.step = firstErrorGroup;
                    }

                    return firstErrorGroup === null;
                };
            },
            validateStepRules(group) {
                const groups = this.formGroups;
            },
            getFieldRules(field, fieldValidations) {
                const rulesBook = this.getFieldValidationsRuleBook(field);
                const rules = [];

                const validations = fieldValidations.split('|');
                validations.forEach((v) => {
                    const parts = v.split(':');
                    if (rulesBook[parts[0]]) {
                        rules.push(rulesBook[parts[0]]);
                    }
                });

                return rules;
            },
            resetFormValidations() {
                this.response = null;
                this.valid = true;
                this.$refs.form.resetValidation();
            },
            validateAndNext() {
                this.valid = this.$refs.form.validate();
                if (this.valid) {
                    this.panels = [this.panels.length];
                }
            },
            openFormInNewTab() {
                this.$emit('new-tab', this.openNewTab, this.formData);
                return false;
            }
        },
    }
</script>

<style scoped>
    .v-expansion-panel::before {
        box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.2), 0px 0px 0px 0px rgba(0, 0, 0, 0.14), 0px 0px 0px 0px rgba(0, 0, 0, 0.12) !important;
    }
    .simple-row .col {
        padding: 4px !important;
    }
</style>