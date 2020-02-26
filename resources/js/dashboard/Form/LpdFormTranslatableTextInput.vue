<template>
    <v-text-field
            ref="inputValue"
            v-model="value[currentLang.id]"
            v-bind="textFieldProps"
            @input="updateInput()"
            :small="inputProps.dense"
    >
        <template v-slot:prepend>
            <v-tooltip top>
                <template v-slot:activator="{ on }">
                    <v-btn
                            small
                            dense
                            rounded
                            outlined
                            v-on="on"
                            class="w70 flex-row"
                            color="secondary"
                            @click="nextLang()"
                    >
                        {{ currentLang.abbr }}
                        <v-icon small>chevron_right</v-icon>
                    </v-btn>
                </template>
                <span>{{ currentLang.title }}</span>
            </v-tooltip>
        </template>
    </v-text-field>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "lpd-form-translatable-text-input",
        props: {
            inputProps: Object,
            group: String,
            initValue: String|Object,
            formId: String,
            lang: {
                type: String,
                default: 'en',
            },
            langList: Array,
        },

        computed: {
            textFieldProps: function() {
                return {
                    ...this.inputProps,
                    label: this.inputProps.label + (this.inputProps.required ? ' *':''),
                }
            },
            ...mapState({
                languages: function(state) {
                    if (this.langList) {
                        return this.langList;
                    }

                    return state.config.language.available;
                },

                defaultLangId: (state) => state.page.options.defaults.language,

                currentLang: function(state) {
                    const languages = this.languages;
                    if (this.currLang) {
                        return this.currLang;
                    }
                    if (this.lang) {
                        const lIndex = languages.map((l) => l.id).indexOf(this.lang);
                        if (lIndex >= 0) {
                            return languages[lIndex];
                        }
                    }

                    const lIndex = languages.map((l) => l.id).indexOf(state.page.options.defaults.language);
                    if (lIndex >= 0) {
                        return languages[lIndex];
                    }
                },
            }),
        },

        data: () => ({
            value: {},
            currLang: null,
        }),

        methods: {
            updateInput: function () {
                this.$emit('input', {
                    default: this.value[this.defaultLangId],
                    translations: this.value,
                });
            },
            nextLang() {
                const curr = this.languages.map((l) => l.id).indexOf(this.currentLang.id);
                this.currLang = this.languages[curr === this.languages.length - 1 ? 0 : curr + 1];
            },
            getLangKey(lang) {
                return this.formId + '-lang-'+this.inputProps.id+'-'+lang.id;
            },
        },

        mounted() {
            if (typeof this.initValue === 'string') {
                this.value[this.currentLang.id] = this.initValue;
            } else if (this.initValue) {
                this.value = this.initValue;
            }

            this.currLangKey = this.getLangKey(this.currentLang);

            this.$emit('input', {
                default: this.value[this.defaultLangId],
                translations: this.value,
            });
        },
    }
</script>

<style scoped>
    .v-btn.w70 {
        width: 70px;
        max-width: 70px;
    }
</style>