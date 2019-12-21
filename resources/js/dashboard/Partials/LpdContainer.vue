<template>
    <v-app>


        <v-navigation-drawer
                absolute
                temporary
                v-model="optionsMenu"
                :right="!rtl"
        >
            <v-toolbar flat>
                <v-toolbar-title>Options</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon v-if="optionsMenu"  @click.stop="optionsMenu = false">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
        </v-navigation-drawer>

        <!-- Sizes your content based upon application components -->
        <v-content>

            <!-- Provides the application the proper gutter -->
            <v-container fluid>
                <v-breadcrumbs :items="breadcrumbs" divider=">"></v-breadcrumbs>

                <v-card>
                    <v-tabs
                            v-model="tab"
                            dark
                            show-arrows
                    >
                        <v-tabs-slider color="lighten-3"></v-tabs-slider>
                        <v-tab
                                v-for="item in tabItems"
                                :key="item.id"
                                :href="'#tab-' + item.id"
                        >
                            {{ item.name }}
                        </v-tab>

                        <v-tab-item
                                key="2"
                                value="tab-2"
                        ></v-tab-item>
                        <v-tab-item
                                key="3"
                                value="tab-3"
                        ></v-tab-item>

                        <v-tab-item
                            key="1"
                            value="tab-1"
                        >
                            <v-card-title>
                                <v-btn text icon @click="showFilters = !showFilters"><v-icon>{{ filtersIcon }}</v-icon></v-btn>
                                Nutrition
                                <v-spacer></v-spacer>
                                <v-text-field
                                        v-model="search"
                                        append-icon="search"
                                        label="Search"
                                        single-line
                                        hide-details
                                ></v-text-field>
                            </v-card-title>
                            <v-divider></v-divider>
                            <div>
                                <v-expand-transition>
                                    <div v-show="showFilters">
                                        <v-card flat>
                                            <v-layout justify-center>
                                                <v-col cols="8" class="text-xs-center mb-10">
                                                    <v-form
                                                            ref="form"
                                                            v-model="valid"
                                                            lazy-validation
                                                    >
                                                        <v-text-field
                                                                label="Name"
                                                                required
                                                        ></v-text-field>

                                                        <v-text-field
                                                                label="E-mail"
                                                                required
                                                        ></v-text-field>

                                                        <v-select
                                                                :items="items"
                                                                :rules="[v => !!v || 'Item is required']"
                                                                label="Item"
                                                                required
                                                        ></v-select>

                                                        <v-checkbox
                                                                :rules="[v => !!v || 'You must agree to continue!']"
                                                                label="Do you agree?"
                                                                required
                                                        ></v-checkbox>

                                                        <v-btn
                                                                :disabled="!valid"
                                                                color="success"
                                                                class="mr-4"
                                                        >
                                                            Validate
                                                        </v-btn>

                                                        <v-btn
                                                                color="error"
                                                                class="mr-4"
                                                        >
                                                            Reset Form
                                                        </v-btn>
                                                    </v-form>
                                                </v-col>
                                            </v-layout>
                                        </v-card>
                                        <v-divider></v-divider>
                                    </div>
                                </v-expand-transition>
                                <v-data-table
                                        :headers="headers"
                                        :items="items"
                                        :search="search"
                                        :expanded.sync="expanded"
                                        single-expand
                                        @click:row="expandRow"
                                >
                                    <template v-slot:item.options="{ item }">
                                        <v-icon
                                                small
                                                class="mr-2"
                                                @click=""
                                        >
                                            edit
                                        </v-icon>
                                        <v-icon
                                                small
                                                @click=""
                                        >
                                            delete
                                        </v-icon>
                                    </template>
                                    <template v-slot:expanded-item="{ headers }">
                                        <td :colspan="headers.length" style="margin: 0; padding: 0; height: auto;">
                                            <v-simple-table dark>
                                                <template v-slot:default>
                                                    <thead>
                                                    <tr>
                                                        <th class="text-end">Property</th>
                                                        <th class="text-start">Value</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="item in headers" :key="item.name">
                                                        <td class="text-end">{{ item.text }}</td>
                                                        <td class="text-start">{{ item.value }}</td>
                                                    </tr>
                                                    </tbody>
                                                </template>
                                            </v-simple-table>
                                        </td>
                                    </template>
                                </v-data-table>
                            </div>
                        </v-tab-item>
                    </v-tabs>
                </v-card>
            </v-container>
        </v-content>

        <v-footer app>
            <!-- -->
        </v-footer>
    </v-app>
</template>

<style lang="scss" scoped>

</style>

<script>
    export default {
        name: 'lpd-container',

        computed: {
            filtersIcon: function() { return this.showFilters ? 'close':'filter_list'; },
            drawerIcon: function() { return this.rtl ? 'chevron_right':'chevron_left'; },
            optionsIcon: function() { return !this.rtl ? 'chevron_right':'chevron_left'; },
        },
        methods: {
            expandRow: function(value) {
                if (!this.expanded.includes(value)) {
                    this.expanded.push(value);
                } else {
                    this.expanded = this.expanded.filter(arrayItem => arrayItem !== value)
                }
            }
        },
        data: () => ({
            tab: 'tab-1',
            rtl: this.$store.state.config.RTL,
            expanded: [],
            search: '',
            headers: [
                {
                    text: 'Id',
                    value: 'id',
                    align: 'start',
                    sortable: true,
                    filterable: true,
                    divider: true,
                    width: 10,
                },
                {
                    text: 'Name',
                    value: 'name',
                    align: 'start',
                    sortable: true,
                    filterable: true,
                    divider: true,
                    width: 40,
                },
                {
                    text: 'Created at',
                    align: 'start',
                    value: 'created_at',
                    sortable: true,
                    filterable: true,
                    divider: true,
                    width: 30,
                },
                {
                    text: 'Flags',
                    align: 'start',
                    value: 'flags',
                    sortable: true,
                    filterable: true,
                    divider: true,
                    width: 20,
                },
                {
                    text: 'Options',
                    align: 'start',
                    sortable: false,
                    filterable: false,
                    divider: true,
                    value: 'options',
                    width: 40,
                },
            ],
            items: [
                {
                    id: 12,
                    name: 'Peyman',
                    created_at: '2019-03-12',
                    flags: 1,
                    options: [],
                },
            ],
            tabItems: [
                {
                    id: 1,
                    name: 'Browse',
                },
                {
                    id: 2,
                    name: 'Reports',
                },
                {
                    id: 3,
                    name: 'Create New',
                },
            ],
            form: {},
            valid: false,
            sidebarMenu: true,
            optionsMenu: false,
            showFilters: false,
            cruds: [
                ["Create", "insert_drive_file"],
                ["Create", "insert_drive_file"],
                ["Create", "insert_drive_file"],
            ],
            breadcrumbs: [
                {
                    href: "#",
                    text: "Link #1",
                },
                {
                    href: "#",
                    text: "Link #2",
                },
                {
                    href: "#",
                    text: "Link #3",
                },
            ]
        })
    }
</script>