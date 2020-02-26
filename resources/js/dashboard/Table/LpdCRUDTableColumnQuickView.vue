<template>
    <td :colspan="12" bgcolor="white">
        <div class="d-flex black--text" color="red">
            <label v-if="params.metadata">{{ params.metadata.name }}</label>
        </div>
    </td>
</template>

<script>
    export default {
        name: "lpd-crud-table-column-quick-view",
        props: {
            item: Object,
            params: Object,
        },
        mounted() {
            console.log(this.item, this.params);
        }
    }
</script>

<style scoped>

</style>