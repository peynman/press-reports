<template>
    <div    class="d-flex flex-row"
            @click.stop=""
            v-if="getDotStr(item, params.label)"
    >
        <v-menu offset-x
                flat
                dense
                tile
                :close-on-content-click="false"
                :elevation="0"
                origin="left center"
                transition="scale-transition"
        >
            <template v-slot:activator="{ on }">
                <v-btn
                        class="align-start"
                        text
                        dense
                        small
                        tile
                        v-on="on"
                >
                    {{ item[params.id] }}#{{ getDotStr(item, params.label) }}
                    <v-icon end small>{{ extendIcon }}</v-icon>
                </v-btn>
            </template>

            <v-card class="bordered">
                <v-btn
                        icon
                        text
                        dense
                        tile
                        small
                        v-for="(link,index) in params.links"
                        :key="item[params.id]-index"
                        :class="activeLink && activeLink.url === link.url ? 'active':''"
                        @click="doLink(link)"
                        :color="activeLink && activeLink.metadata.name === link.metadata.name ? 'primary':''"
                >
                    <v-icon
                            small
                    >
                        {{ link.icon }}
                    </v-icon>
                </v-btn>
            </v-card>
        </v-menu>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "lpd-crud-table-column-object-links",
        props: {
            item: Object,
            params: Object,
        },

        computed: {
            extendIcon: function() {
                return this.rtl ? 'mdi-chevron-left':'mdi-chevron-right';
            },
            ...mapState({
                rtl: state => state.config.rtl,
            }),
            themeName: function() {
                return this.$vuetify.theme.dark ? 'dark':'light'
            },
            backgroundColor: function() {
                console.log(this.themeName, this.$vuetify.theme);
                return this.$vuetify.theme.themes[this.themeName].background
            }
        },
        data: () => ({
            activeLink: null,
            expanded: false,
        }),
        methods: {
            doLink(link) {
                if (this.activeLink && this.activeLink.metadata.name === link.metadata.name) {
                    this.activeLink = null;
                    this.$emit('close-extend', {item: this.item, link});
                } else {
                    this.activeLink = link;
                    this.$emit('open-extend', {item: this.item, link});
                }
            }
        }
    }
</script>

<style scoped>
    .v-menu__content {
        box-shadow: none;
    }

    .bordered {
        border: solid 1px var(--v-secondary-gray-1);
    }
</style>