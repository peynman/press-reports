<template>
  <v-card></v-card>
</template>

<script>
export default {
  name: "pm-card",
  spec: {
    commands: {
      insertCard: (state, dispatch, editorView) => {
        
      },
    }
  },
  nodes: {
    card: {
      content: "cardTitle",
      group: "block",
      parseDOM: [
        {
          tag: "div",
          attrs: {
            'parser-id': "pm-card",
          }
        }
      ],
      toDOM() {
        return ["pm-card", 0];
      }
    }
  }
};
</script>