<template>
    <div class="d-flex flex-column mb-4 mt-4">
        <div class="d-flex flex-row align-center">
            <label :class="inputProps.error ? 'red--text':''">{{ inputProps.label }}</label>
            <v-btn text icon @click="toggleAppend()"><v-icon>{{ append ? 'keyboard_arrow_up':'add_box' }}</v-icon></v-btn>
        </div>
        <v-simple-table fixed-header dense>
            <template v-slot:default>
                <thead>
                    <tr>
                        <th
                                v-for="header in inputProps.headers"
                                :key="formId+'-header-'+header.title"
                                class="col-4 blue-grey darken-1 white--text text-start">
                            {{ header.title }}
                        </th>
                        <th class="blue-grey darken-1 white--text text-start"></th>
                    </tr>
                </thead>
                <tbody>
                <tr v-if="append" :key="formId+'-item-append'">
                    <td v-for="field in formFields">
                        <v-form @keyup.native.enter="appendToObjectsList()">
                            <component
                                    ref="elements"
                                    v-bind="field.props"
                                    :key="formId+'-inner-'+field.id"
                                    :is="field.component"
                                    v-model="appendObject[field.id]"
                            />
                        </v-form>
                    </td>
                    <td>
                        <v-btn text icon @click="appendToObjectsList()" color="success"><v-icon>check_box</v-icon></v-btn>
                        <v-btn text icon @click="closeAppend()" color="secondary"><v-icon>cancel</v-icon></v-btn>
                    </td>
                </tr>
                <tr
                        v-for="(item,index) in objects"
                        :key="formId+'-item-'+index"
                >
                    <td v-for="header in inputProps.headers">{{ item[header.column] }}</td>
                    <td>
                        <v-btn text icon color="deep-orange" @click="removeObjectFromList(index)"><v-icon>cancel</v-icon></v-btn>
                    </td>
                </tr>
                </tbody>
            </template>
        </v-simple-table>
        <v-divider></v-divider>
        <div class="d-flex flex-column" v-if="inputProps.error">
            <div v-for="err in inputProps['error-messages']" class="red--text">
                {{ err }}
            </div>
        </div>
        <v-divider></v-divider>
    </div>
</template>

<script>
    export default {
        name: "lpd-form-inline-table",
        props: {
            inputProps: Object,
            formId: String,
            group: String,
            initValue: Array,
        },
        computed: {
            formFields: function() {
                const formFields = [];

                this.inputProps.fields.forEach((field) => {
                    formFields.push({
                        id: field.id,
                        component: 'lpd-form-'+field.input,
                        props: {
                            inputProps: {
                                'dense': true,
                                ...field
                            }
                        },
                    });
                });
                return formFields;
            },
            innerFormId: function() {
                return this.getRandomString(5);
            },
            panels: function() {
                return this.append ? [0]:[];
            }
        },
        data: () => ({
            objects: [],
            append: false,
            appendObject: {},
        }),
        mounted() {
            if (this.initValue) {
                this.objects.push(...this.initValue);
            }
        },
        methods: {
            appendToObjectsList: function() {
                const copy = {...this.appendObject};
                this.objects.unshift(copy);
                this.$emit('input', this.objects);

                return false;
            },
            removeObjectFromList: function(index) {
                this.objects.splice(index, 1);
                this.$emit('input', this.objects);
            },
            openAppend: function() {
                this.append = true;
            },
            closeAppend: function() {
                this.append = false;
            },
            toggleAppend: function() {
                if (this.append) {
                    this.closeAppend();
                } else {
                    this.openAppend();
                }
            },
        },
    }
</script>

<style scoped>

</style>