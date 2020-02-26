<template>
    <v-skeleton-loader
            :loading="loading"
            transition="fade-transition"
            type="list-item-two-line"
    >
        <div class="d-flex flex-column">
            <component
                    v-if="viewComponent"
                    :is="viewComponent"
                    :metadata="metadata"
                    :init-value="initValue"
                    :minimal="true"
                    @new-tab="newTab"
            />
            <v-alert
                    v-model="alert.show"
                    :type="alert.type"
                    transition="slide-y-transition"
                    border="top"
                    dense
                    dismissible
                    class="ma-5"
            >
                {{ alert.message }}
            </v-alert>
        </div>
    </v-skeleton-loader>
</template>

<script>
    export default {
        name: "lpd-crud-browse-tab",
        props: {
            fetch: Object,
            initValue: Object|Array,
        },

        computed: {
            alert: function() {
                if (!this.response) {
                    return {
                        show: false,
                    }
                }

                return {
                    show: this.response.status !== 200,
                    message: this.response.data.message,
                    type: this.response.status !== 200 ? 'error':'success',
                    messages: this.response.data.errors,
                }
            },
        },

        data: () => ({
            response: null,
            metadata: null,
            loading: false,
            viewComponent: null,
        }),

        mounted() {
            this.updateContent();
        },

        methods: {
            updateContent: function() {
                this.loading = true;
                this.axios.get(this.fetch.url)
                .then((response) => {
                    this.metadata = response.data.config.content.metadata;
                    this.viewComponent = response.data.config.content.component;
                    this.response = response;
                }).catch((error) => {
                    this.response = error.response;
                }).finally(() => {
                    this.loading = false;
                });
            },

            newTab(link, data) {
                console.log('middle', link, data);
                this.$emit('new-tab', link, data);
            },
        }
    }
</script>

<style scoped>
    .browse-loader-tab {
        min-height: 100px;
    }
</style>