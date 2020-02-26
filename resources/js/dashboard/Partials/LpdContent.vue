<template>
    <v-content>
        <v-container fluid>
            <component :is="content" v-bind="contentProps"/>
        </v-container>
    </v-content>
</template>

<script>
    import Vue from 'vue'
    import { mapMutations, mapState } from 'vuex'
    import mutations from '../Lib/mutations'

    export default {
        name: "lpd-content",
        computed: {
            ...mapState({
                content: state => state.page.content.component,
                contentProps: state => state.page.content.props,
                rtl: state => state.config.rtl,
            }),
        },

        mounted() {

        }
    }
</script>

<style scoped>

</style>