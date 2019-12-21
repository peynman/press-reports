<template>
    <v-navigation-drawer
            absolute
            temporary
            v-model="visible"
            :right="!rtl"
    >
        <v-toolbar flat>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon v-if="visible"  @click.stop="setOptionsMenuVisible(false)">
                <v-icon>close</v-icon>
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
        name: "lpd-options-menu",
        computed: {
            visible: {
                get() { return this.$store.state.page.options.visible; },
                set(v) { this.setOptionsMenuVisible(v); }
            },
            ...mapState({
                drawerIcon: state => state.config.rtl ? 'chevron_right':'chevron_left',
                rtl: state => state.config.rtl,
                items: state => state.page.options.items,
                title: state => state.page.options.title,
            }),
        },
        methods: {
            ...mapMutations([
                mutations.setOptionsMenuVisible,
            ])
        }
    }
</script>

<style scoped>

</style>