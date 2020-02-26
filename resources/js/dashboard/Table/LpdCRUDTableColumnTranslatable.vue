<template>
    <div class="d-flex flex-row justify-cente align-center align-content-center " @click.stop="">
        {{ item.translations[column][extras.currentLang.id] }}
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "lpd-crud-table-column-translatable",
        props: {
            item: Object,
            params: Object|Array,
            column: String,
            extras: Object,
        },

        computed: {
        },

        methods: {
        },

        data: () => ({
        }),

    }
</script>

<style scoped>

</style>