<template>
    <v-card>
        <v-tabs
                v-model="currentTab"
                dark
                show-arrows
                v-if="! minimal"
        >
            <v-tabs-slider color="lighten-3"></v-tabs-slider>
            <draggable class="d-flex">
                <v-tab
                        v-for="item in tabs"
                        :key="item.id"
                        :href="'#'+item.id"
                >
                    {{ item.title }}
                </v-tab>
                <v-tab
                        v-for="item in extraTabs"
                        :key="item.id"
                        :href="'#'+item.id"
                >
                    {{ item.title }}
                    <v-btn text small icon v-if="item.close !== false" @click.stop="">
                        <v-icon small @click.stop="closeTab(item)">mdi-close</v-icon>
                    </v-btn>
                </v-tab>
            </draggable>

            <v-tab-item
                    v-for="item in tabs"
                    :key="item.id"
                    :value="item.id"
            >
                <div v-if="item.display">
                    <component :is="item.display.component" v-bind="item.display.props" @new-tab="linkClicked"/>
                </div>
            </v-tab-item>
            <v-tab-item
                    v-for="item in extraTabs"
                    :key="item.id"
                    :value="item.id"
            >
                <div v-if="item.display">
                    <component :is="item.display.component" v-bind="item.display.props" @new-tab="linkClicked"/>
                </div>
            </v-tab-item>

            <v-menu
                    v-model="sidebar.visBrowse"
                    :close-on-content-click="false"
                    offset-y
                    flat
                    dense
                    tile
                    :elevation="2"
                    transition="scale-transition"
                    :nudge-width="200"
            >
                <template v-slot:activator="{ on }">
                    <v-btn
                            tile
                            text
                            icon
                            class="align-self-center pa-1 ps-4"
                            v-on="on"
                    >
                        <v-icon>mdi-plus-box</v-icon>
                        <v-icon>mdi-menu-down</v-icon>
                    </v-btn>
                </template>

                <v-list
                        dense
                >
                    <component
                            v-for="(item, index) in sidebar.items"
                            :key="getMenuItemKey(item, index)"
                            :is="item.component"
                            v-bind="item.props"
                            :link-clicked="linkClicked"
                    />
                </v-list>
            </v-menu>
        </v-tabs>
        <lpd-crud-table v-if="minimal" :metadata="metadata"/>
    </v-card>
</template>

<script>
    import { mapMutations, mapState } from 'vuex'
    import LpdSideMenu from "../Partials/LpdSideMenu";
    import draggable from 'vuedraggable'

    export default {
        components: {
            LpdSideMenu,
            draggable,
        },
        name: "lpd-crud-browse",
        props: {
            metadata: Object,
            minimal: {
                type: Boolean,
                default: false,
            }
        },
        computed: {
            tabs: function() {
                return [
                    {
                        title: this.metadata.resource.title.plural,
                        id: 'lpd-browse-'+this.metadata.name,
                        index: 0,
                        display: {
                            component: 'lpd-crud-table',
                            props: {
                                metadata: this.metadata,
                            }
                        }
                    },
                ];
            },
            ...mapState({
                sidebar: state => state.page.sidebar,
            }),
        },
        data: function() {
            return {
                currentTab: 'lpd-browse-'+this.metadata.name,
                extraTabs: [],
                orderedTabs: {},
            };
        },
        methods: {
            linkClicked: function(link, data = null) {
                this.sidebar.visBrowse = false;

                console.log('new tab', link, data);
                const index = this.extraTabs.length + 2;
                this.extraTabs.push({
                    title: link.title,
                    name: link.url,
                    id: 'lpd-browse-tab-'+index+link.url,
                    index: index,
                    display: {
                        component: 'lpd-crud-browse-tab',
                        props: {
                            fetch: link,
                            initValue: data,
                        }
                    }
                });
                this.currentTab = 'lpd-browse-tab-'+index+link.url;

                return false;
            },

            closeTab: function(tab) {
                this.$delete(this.extraTabs, this.extraTabs.map((m) => m.id).indexOf(tab.id));
                if (tab.id === this.currentTab) {
                    this.currentTab = 'lpd-browse-'+this.metadata.name;
                }
            },


        }
    }
</script>

<style scoped>
    .draggable-tabs {
        display: flex;
    }
</style>