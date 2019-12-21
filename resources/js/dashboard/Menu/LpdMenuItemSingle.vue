<template>
    <v-list-item :href="url">
        <v-list-item-icon>
            <v-icon v-if="icon">{{ icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{ title }}</v-list-item-title>
    </v-list-item>
</template>

<script>
    export default {
        name: "lpd-menu-item-single",
        props: {
            url: String,
            title: String,
            icon: String,
        },
    }
</script>

<style scoped>

</style>