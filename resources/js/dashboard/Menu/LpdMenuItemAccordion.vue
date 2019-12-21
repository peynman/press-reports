<template>
    <v-list-group
            :prepend-icon="icon"
            no-action
            :sub-group="subgroup"
    >
        <template v-slot:activator>
            <v-list-item-content>
                <v-list-item-title>{{ title }}</v-list-item-title>
            </v-list-item-content>
        </template>

        <component
                v-for="(item, index) in items"
                :key="getMenuItemKey(item, index)"
                :is="item.component"
                v-bind="{...item.props, subgroup: true}"
        />
    </v-list-group>
</template>

<script>
    export default {
        name: "lpd-menu-item-accordion",
        props: {
            subgroup: {
                type: Boolean,
                default: false,
            },
            title: String,
            icon: String,
            items: Array,
        }
    }
</script>

<style scoped>

</style>