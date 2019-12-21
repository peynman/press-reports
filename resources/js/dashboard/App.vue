<template>
    <v-app>
        <lpd-app-bar/>
        <lpd-side-menu/>
        <lpd-options-menu/>
    </v-app>
</template>

<script>
    import Vue from 'vue'
    import Vuetify from 'vuetify'
    import Vuex from 'vuex'
    import mutations from './Lib/mutations'
    import VuetifyLib from 'vuetify/lib'

    Vue.use(Vuetify);
    Vue.use(Vuex);

    import LpdContainer from './Partials/LpdContainer';
    import LpdAppBar from "./Partials/LpdAppBar";
    import LpdSideMenu from "./Partials/LpdSideMenu";
    import LpdOptionsMenu from "./Partials/LpdOptionsMenu";

    Vue.component('lpd-container', LpdContainer);
    Vue.component('lpd-app-bar', LpdAppBar);
    Vue.component('lpd-side-menu', LpdSideMenu);
    Vue.component('lpd-options-menu', LpdOptionsMenu);

    import LpdMenuItemSingle from './Menu/LpdMenuItemSingle';
    import LpdMenuItemAccordion from './Menu/LpdMenuItemAccordion';
    import LpdMenuItemHeader from './Menu/LpdMenuItemHeader';

    Vue.component('lpd-menu-item-single', LpdMenuItemSingle);
    Vue.component('lpd-menu-item-accordion', LpdMenuItemAccordion);
    Vue.component('lpd-menu-item-header', LpdMenuItemHeader);

    const store = new Vuex.Store({
        state: {
            config: {
                rtl: window.DashboardConfig.RTL,
            },
            page: {
                title: window.DashboardConfig.page.title,
                sidebar: {
                    visible: true,
                    items: window.DashboardConfig.sideMenuItems
                },
                options: {
                    visible: false,
                    title: window.DashboardConfig.options.title,
                    items: [],
                },
            }
        },
        mutations: {
            [mutations.setSideMenuVisible](state, status) {
                state.page.sidebar.visible = status;
            },
            [mutations.setOptionsMenuVisible](state, status) {
                state.page.options.visible = status;
            }
        },
        actions: {},
        getters: {
            isRTL: state => state.config.rtl,
            isSideMenuVisible: state => state.page.sidebar.visible,
            isOptionsMenuVisible: state => state.page.options.visible,
        }
    });

    Vue.mixin({
        methods: {
            getMenuItemKey(item, index) {
                return item.component + item.props.title.replace(/\s+/g, '-').toLowerCase() + '-' + index;
            }
        }
    });

    const vuetify = new VuetifyLib({
        rtl: window.DashboardConfig.RTL,
    });
    export default {
        name: 'lpd-app',
        store,
        vuetify
    }
</script>