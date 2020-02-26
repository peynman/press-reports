<template>
  <p :class="pClass" :style="pStyles" :data-parser-id="parserId"></p>
</template>

<script>
import {
  getAttrsInNodeOfType,
  isAttrEqInNodeOfType,
  updateAttrOfNodeOfTypeCommand,
  ParagraphDomSampler
} from "./ProseMirrorHelpers";

export default {
  name: "pm-paragraph",
  props: {
    align: String,
    fontSize: String,
    textColor: String,
    backColor: String,
    parserId: String,
  },
  computed: {
    pStyles: function() {
      const stylesList = [];
      if (this.fontSize) {
        if (['px', 'em'].some((suff) => (this.fontSize.endsWith(suff)))) {
          stylesList.push(`font-size: ${this.fontSize}`);
        }
      }
      return stylesList.join(";");
    },
    pClass: function() {
      const classesList = [];
      if (this.align) {
        if (['left', 'right', 'center', 'end', 'start'].indexOf(this.align) >= 0) {
          classesList.push(`text-${this.align}`);
        }
      }

      return classesList.join(" ");
    },
  },
  spec: {
    commands: {
      alignRight: updateAttrOfNodeOfTypeCommand(
        state => state.schema.nodes.paragraph,
        {
          align: "right"
        }
      ),
      alignLeft: updateAttrOfNodeOfTypeCommand(
        state => state.schema.nodes.paragraph,
        {
          align: "left"
        }
      ),
      alignCenter: updateAttrOfNodeOfTypeCommand(
        state => state.schema.nodes.paragraph,
        {
          align: "center"
        }
      ),
      fontSize: size =>
        updateAttrOfNodeOfTypeCommand(state => state.schema.nodes.paragraph, {
          fontSize: size
        })
    },
    isActive: {
      alignRight: isAttrEqInNodeOfType(state => state.schema.nodes.paragraph, {
        align: "right"
      }),
      alignLeft: isAttrEqInNodeOfType(state => state.schema.nodes.paragraph, {
        align: "left"
      }),
      alignCenter: isAttrEqInNodeOfType(state => state.schema.nodes.paragraph, {
        align: "center"
      })
    },
    getValues: {
      fontSize: state => {
        const attrs = getAttrsInNodeOfType(
          state => state.schema.nodes.paragraph
        )(state);
        if (attrs && attrs.fontSize) {
          return attrs.fontSize;
        }

        return "14px";
      }
    }
  },
  nodes: {
    paragraph: {
      attrs: {
        align: {
          default: null
        },
        fontSize: {
          default: "14px"
        },
        textColor: {
          default: null
        },
        backColor: {
          default: null
        }
      },
      content: "inline*",
      group: "block",
      parseDOM: [
        {
          tag: "p",
          attrs: dom => {
            const attrs = {};
            return attrs;
          }
        }
      ],
      toDOM(node) {
        return [
          "pm-paragraph",
          {
            props: node.attrs,
          },
          0
        ];
      }
    }
  }
};
</script>
