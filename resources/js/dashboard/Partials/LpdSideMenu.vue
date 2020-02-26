<template>
    <v-navigation-drawer
            app
            v-model="visible"
            :right="rtl"
    >
        <v-toolbar flat v-if="toolbar && !toolbar.hide">
            <v-toolbar-title>{{ username }}</v-toolbar-title>

            <v-spacer></v-spacer>
            <v-btn icon v-if="visible"  @click.stop="setSideMenuVisible(false)">
                <v-icon>{{ drawerIcon }}</v-icon>
            </v-btn>
        </v-toolbar>
        <v-list
                dense
        >
            <component
                    v-for="(item, index) in items"
                    :key="getMenuItemKey(item, index)"
                    :is="item.component"
                    v-bind="item.props"
            />
        </v-list>
    </v-navigation-drawer>
</template>

<script>
    import { mapMutations, mapState } from 'vuex'
    import mutations from '../Lib/mutations'

    export default {
        name: "lpd-side-menu",
        props: {
            toolbar: {
                type: Object,
                default: function () {
                    return {}
                },
            },
        },
        computed: {
            visible: {
                get() { return this.$store.state.page.sidebar.visible; },
                set(v) { this.setSideMenuVisible(v); }
            },
            ...mapState({
                drawerIcon: state => state.config.rtl ? 'chevron_right':'chevron_left',
                rtl: state => state.config.rtl,
                items: state => state.page.sidebar.items,
                username: state => state.page.user.name,
            }),
        },
        methods: {
            ...mapMutations([
                mutations.setSideMenuVisible,
            ])
        }
    }
</script>

<style scoped>

</style>