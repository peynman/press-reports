<template>
    <div class="d-flex flex-row justify-cente align-center align-content-center " @click.stop="">
        <v-btn class="flex-grow-0 flex-shrink-0" text icon small dense @click="nextMode"><v-icon small>chevron_left</v-icon></v-btn>
        <label class="flex-grow-1 flex-shrink-1 text-center">{{ getDateTimeString(item[column], params.format, mode) }}</label>
        <v-btn class="flex-grow-0 flex-shrink-0" text icon small dense @click="prevMode"><v-icon small>chevron_right</v-icon></v-btn>
    </div>
</template>

<script>
    import {mapState} from "vuex";

    export default {
        name: "lpd-crud-table-column-datetime",
        props: {
            item: Object,
            params: Object,
            column: String,
        },

        computed: {
            mode: {
                get() { return this.innerMode ? this.innerMode: this.globalMode; },
                set(m) { this.innerMode = m; },
            },
            ...mapState({
                modes: state => state.config.datetime.modes,
                globalMode: function(state) {
                    if (state.page.options.values[this.column+'_format']) {
                        return state.page.options.values[this.column+'_format'];
                    }

                    return null;
                },
            })
        },

        methods: {
            nextMode: function() {
                const currentIndex = this.modes.indexOf(this.mode);
                this.mode = this.modes[currentIndex === this.modes.length-1 ? 0: currentIndex+1];
            },
            prevMode: function() {
                const currentIndex = this.modes.indexOf(this.mode);
                this.mode = this.modes[currentIndex === 0 ? this.modes.length-1: currentIndex-1];
            },
        },

        data: () => ({
            innerMode: null,
        }),

    }
</script>

<style scoped>

</style>