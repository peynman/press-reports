<template>
  <v-card-title></v-card-title>
</template>

<script>
export default {
  name: "pm-card-title",
  nodes: {
    cardTitle: {
      content: "inline*",
      group: "block",
      parseDOM: [
        {
          tag: "div",
          attrs: {
            domid: "ps-title"
          }
        }
      ],
      toDOM() {
        return ["pm-card-title", 0];
      }
    }
  }
};
</script>