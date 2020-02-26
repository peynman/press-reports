<template>
  <div class="ProseMirror mt-2">
    <div class="d-flex flex-row" :id="`${formId}-${inputProps.id}-toolbar`" v-if="editorMenu">
      <v-tooltip
        top
        v-for="(item, index) in editorMenuItems"
        :key="`${formId}-editor-menu-${index}`"
      >
        <template v-slot:activator="{on}">
          <v-btn
            v-if="item.type === 'btn'"
            dense
            small
            icon
            v-on="on"
            @click="editorMenu.onClick(item.command)"
            v-show="item.isVisible"
            :color="item.isActive ? 'primary':''"
          >
            <v-icon small>{{item.icon}}</v-icon>
          </v-btn>
          <v-text-field
            class="prosemirror-text-field"
            v-else-if="item.type === 'text-field'"
            x-small
            small
            dense
            v-model="item.value"
            :prepend-icon="item.icon"
            @keyup.native="updateMenuItemValue($event, item)"
          ></v-text-field>
        </template>
        {{item.title}}
      </v-tooltip>
    </div>
    <div :id="`${formId}-${inputProps.id}-editor`"></div>
    <div style="display: none;" :id="`${formId}-${inputProps.id}-content`"></div>
  </div>
</template>

<script>
import Vue from "vue";
import { EditorState, Plugin } from "prosemirror-state";
import { EditorView } from "prosemirror-view";
import { Schema, DOMParser, DOMSerializer } from "prosemirror-model";
import { schema } from "prosemirror-schema-basic";
import { addListNodes } from "prosemirror-schema-list";
import { keymap } from "prosemirror-keymap";
import { history } from "prosemirror-history";
import {
  toggleMark,
  setBlockType,
  wrapIn,
  baseKeymap
} from "prosemirror-commands";
import { MenuItem } from "blockly";

import CardComp from "./Card";
import CardTitleComp from "./CardTitle";
import ParagraphComp from "./Paragraph";

import MenuBar, { menubar } from "./ProseMirrorMenuBar";
const exampleSchemeSpec = schema;

export default {
  name: "lpd-form-editorjs-prose-mirror-editor",
  props: {
    inputProps: Object,
    initValue: Object,
    formId: String,
    group: String
  },
  computed: {
    editorComponents: () => ({
      "pm-card": CardComp,
      "pm-card-title": CardTitleComp,
      "pm-paragraph": ParagraphComp
    }),
    nodes: function() {
      let nodes = {
        doc: {
          content: "block+"
        },
        text: {
          group: "inline"
        },
        paragraph: exampleSchemeSpec.nodes.paragraph,
        ...CardComp.nodes,
        ...CardTitleComp.nodes,
        ...ParagraphComp.nodes
      };

      return nodes;
    },
    marks: function() {
      let marks = exampleSchemeSpec.spec.marks;
      return marks;
    },
    schema: function() {
      return new Schema({
        nodes: this.nodes,
        marks: this.marks
      });
    },
    editorMenuItems: {
      get() {
        if (!this.menuItems) {
          this.menuItems = [
            {
              type: "btn",
              title: "Alight Left",
              icon: "mdi-format-align-left",
              command: ParagraphComp.spec.commands.alignLeft,
              isActiveCallback: ParagraphComp.spec.isActive.alignLeft,
              isActive: false,
              isVisible: false
            },
            {
              type: "btn",
              title: "Alight Center",
              icon: "mdi-format-align-center",
              command: ParagraphComp.spec.commands.alignCenter,
              isActiveCallback: ParagraphComp.spec.isActive.alignCenter,
              isActive: false,
              isVisible: false
            },
            {
              type: "btn",
              title: "Alight Right",
              icon: "mdi-format-align-right",
              command: ParagraphComp.spec.commands.alignRight,
              isActiveCallback: ParagraphComp.spec.isActive.alignRight,
              isActive: false,
              isVisible: false
            },
            {
              type: "text-field",
              title: "Font size",
              icon: "mdi-format-size",
              command: ParagraphComp.spec.commands.fontSize,
              updateValueCallback: ParagraphComp.spec.getValues.fontSize,
              value: "12px",
              isVisible: false
            },
            {
              type: "btn",
              title: "Bold",
              icon: "mdi-format-bold",
              command: toggleMark(this.schema.marks.strong),
              mark: this.schema.marks.strong,
              isActive: false,
              isVisible: false
            },
            {
              type: "btn",
              title: "Italic",
              icon: "mdi-format-italic",
              command: toggleMark(this.schema.marks.em),
              mark: this.schema.marks.em,
              isActive: false,
              isVisible: false
            },
            {
              type: "devider",
            },
          ];
        }
        return this.menuItems;
      },
      set(v) {
        console.log(v);
      }
    }
  },
  data: () => ({
    editor: null,
    editorMenu: null,
    view: null,
    editorValues: {
      menuItems: {}
    },
    menuItems: null
  }),

  methods: {
    updateInput(json) {
      this.$emit("input", json);
    },
    updateMenuItemValue(e, item) {
      if (e.keyCode === 13) {
        this.editorMenu.onClick(item.command, item.value);
      }
    }
  },
  mounted() {
    const self = this;

    const oldSpec = DOMSerializer.renderSpec;
    DOMSerializer.renderSpec = (doc, structure, xmlNS = null) => {
      console.log(structure, xmlNS);

      if (typeof structure == "string")
        return { dom: doc.createTextNode(structure) };
      if (structure.nodeType != null) return { dom: structure };
      let tagName = structure[0],
        space = tagName.indexOf(" ");
      if (space > 0) {
        xmlNS = tagName.slice(0, space);
        tagName = tagName.slice(space + 1);
      }
      let contentDOM = null;
      let dom = null;
      let vue = false;
      if (xmlNS) {
        dom = doc.createElementNS(xmlNS, tagName);
      } else {
        if (self.editorComponents[tagName]) {
          const comClass = Vue.extend(self.editorComponents[tagName]);
          const comp = new comClass({
            propsData: structure[1].props,
          });
          comp.$mount();
          dom = comp.$el;
          dom.editorComponentRef = comp;
          vue = true;
        } else {
          dom = doc.createElement(tagName);
        }
      }
      let attrs = structure[1],
        start = 1;
      if (
        attrs &&
        typeof attrs == "object" &&
        attrs.nodeType == null &&
        !Array.isArray(attrs)
      ) {
        start = 2;
        if (!vue) {
          for (let name in attrs)
            if (attrs[name] != null) {
              let space = name.indexOf(" ");
              if (space > 0)
                dom.setAttributeNS(
                  name.slice(0, space),
                  name.slice(space + 1),
                  attrs[name]
                );
              else dom.setAttribute(name, attrs[name]);
            }
        }
      }
      for (let i = start; i < structure.length; i++) {
        let child = structure[i];
        if (child === 0) {
          if (i < structure.length - 1 || i > start)
            throw new RangeError(
              "Content hole must be the only child of its parent node"
            );
          return { dom, contentDOM: dom };
        } else {
          let {
            dom: inner,
            contentDOM: innerContent
          } = DOMSerializer.renderSpec(doc, child, xmlNS);
          dom.appendChild(inner);
          if (innerContent) {
            if (contentDOM) throw new RangeError("Multiple content holes");
            contentDOM = innerContent;
          }
        }
      }
      return { dom, contentDOM };
    };

    let state = null;
    // prosemirror editor plugins
    const plugins = [
      // Menubar plugin to update components on state change
      menubar(
        self.editorMenuItems.filter(
          i => i.command
        ) /** pass only items with command option available */,
        this
      ),
      keymap(baseKeymap),
      history()
    ];

    // init prosemirror editor state from component initValue or empty,
    if (this.initValue) {
      state = EditorState.fromJSON(
        {
          schema: this.schema,
          plugins: plugins
        },
        this.initValue
      );
    } else {
      state = EditorState.create({
        schema: this.schema,
        plugins: plugins
      });
    }

    // init prosemirror editor view
    this.editor = new EditorView(
      document.getElementById(`${this.formId}-${this.inputProps.id}-editor`),
      {
        state: state,
        dispatchTransaction(transaction) {
          self.editor.state = self.editor.state.apply(transaction);
          self.editor.updateState(self.editor.state);
          self.updateInput(self.editor.state.toJSON());
        }
      }
    );
  }
};
</script>

<style scoped>
.prosemirror-text-field {
  max-width: 80px;
}
</style>