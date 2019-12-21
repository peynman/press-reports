<template>
    <v-app-bar
            dark
            app
    >
        <v-app-bar-nav-icon v-if="!isSideMenuVisible" @click.stop="setSideMenuVisible(true)"></v-app-bar-nav-icon>

        <v-toolbar-title>{{ title }}</v-toolbar-title>

        <v-spacer></v-spacer>

        <v-menu
                left
                bottom
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon>email</v-icon>
                </v-btn>
            </template>

            <v-list>

            </v-list>

        </v-menu>

        <v-menu
                left
                bottom
        >
            <template v-slot:activator="{ on }">
                <v-btn icon v-on="on">
                    <v-icon>notifications</v-icon>
                </v-btn>
            </template>

            <v-list>

            </v-list>

        </v-menu>

        <v-btn outlined dark @click="setOptionsMenuVisible(!isOptionsMenuVisible)">
            {{ optionsTitle }}
        </v-btn>

    </v-app-bar>
</template>

<style  lang="scss" scoped>

</style>

<script>
    import { mapMutations, mapState } from 'vuex'
    import mutations from '../Lib/mutations'

    export default {
        name: 'lpd-app-bar',
        computed: {
            ...mapState({
                isSideMenuVisible: state => state.page.sidebar.visible,
                isOptionsMenuVisible: state => state.page.options.visible,
                rtl: state => state.config.rtl,
                title: state => state.page.title,
                optionsTitle: state => state.page.options.title,
            }),
        },
        methods: {
            ...mapMutations([
                mutations.setSideMenuVisible,
                mutations.setOptionsMenuVisible,
            ])
        }
    }
</script>