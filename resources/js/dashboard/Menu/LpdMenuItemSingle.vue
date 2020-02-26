<template>
    <v-list-item ref="clickable"  :href="url" @click.native.prevent="onLinkClicked(false)" >
        <v-list-item-icon  v-if="icon">
            <v-icon>{{ icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{ title }}</v-list-item-title>
        <v-lit-item-icon
                v-if="links"
                v-for="(link, index) in links"
                :key="url+'-link-'+index"
        >
            <v-btn
                    text
                    tile
                    icon
                    @click.native.prevent="onLinkClicked(link)" :href="link.url"
            >
                <v-icon>{{ link.icon }}</v-icon>
            </v-btn>
        </v-lit-item-icon>
    </v-list-item>
</template>

<script>
    export default {
        name: "lpd-menu-item-single",
        props: {
            url: String,
            title: String,
            icon: String,
            links: Array,
            linkClicked: {
                type: Function,
                default: null,
            },
        },

        methods: {
            onLinkClicked: function(alternate) {
                if (this.linkClicked) {
                    return this.linkClicked({
                        url: this.url,
                        icon: this.icon,
                        title: this.title,
                        alternate: alternate,
                    });
                }

                window.location.href = this.url;
            }
        }
    }
</script>

<style scoped>

</style>