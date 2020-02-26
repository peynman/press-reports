<template>
  <div class="d-flex flex-column">
    <div class="d-flex flex-row align-center mb-1">
      <label :class="inputProps.error ? 'red--text me-1':'me-1'">{{ inputProps.label }}</label>
      <v-tooltip top v-for="(item, index) in editorModes" :key="`${formId}-${index}-mode`">
        <template v-slot:activator="{on}">
          <v-btn
            text
            icon
            :color="options.mode === item.mode ? 'primary':'secondary'"
            @click="setMode(item.mode)"
            v-on="on"
          >
            <v-icon>{{item.icon}}</v-icon>
          </v-btn>
        </template>
        {{item.title}}
      </v-tooltip>
      <v-tooltip top v-if="options.mode === 'queries'">
        <template v-slot:activator="{on}">
          <v-btn
            small
            icon
            text
            dense
            v-on="on"
            @click="toggleBlocklyMode()"
            :color="options.blockly.mode === 'editor' ? 'secondary':'primary'"
            :outlined="options.blockly.mode === 'code'"
          >
            <v-icon small>{{ options.blockly.mode === 'editor' ? 'code':'edit' }}</v-icon>
          </v-btn>
        </template>
        Toggle blockly code/editor
      </v-tooltip>
      <v-spacer></v-spacer>
      <v-btn-toggle rounded dense>
        <v-btn small icon text dense>
          <v-icon small @click="downloadWorkspace">mdi-download</v-icon>
        </v-btn>
        <v-btn small icon text dense>
          <v-icon small @click="importWorkspace">mdi-upload</v-icon>
        </v-btn>
        <v-btn small icon text dense>
          <v-icon small>mdi-chevron-down</v-icon>
        </v-btn>
      </v-btn-toggle>
    </div>
    <div v-if="options.mode === 'editor'">
      <prose-mirror-editor :input-props="{id: `${inputProps.id}-prose-mirror`}" :form-id="formId" v-model="json.content" :init-value="json.content"/>
    </div>
    <div
      :style="{'display': options.mode === 'code'? 'flex':'none'}"
      ref="codeEditor"
      style="width: 100%; height: 400px;"
    ></div>
    <lpd-form-key-value
      v-if="options.mode === 'trans'"
      :form-id="formId"
      :init-value="json.translations"
      v-model="json.translations"
      :input-props="transProps"
    ></lpd-form-key-value>

    <div v-show="options.mode === 'queries'">
      <div
        v-show="options.blockly.mode === 'editor'"
        ref="blockEditor"
        style="width: 100%; height: 400px;"
      ></div>
      <code
        v-show="options.blockly.mode === 'code'"
        class="pa-2"
        style="width: 100%; min-height: 400px;"
      >{{ blockEditorCode }}</code>
    </div>
  </div>
</template>

<script>
import ProseMirrorEditor from './EditorJS/ProseMirrorEditor'
import JSONEditor from "jsoneditor";
import Blockly, { Block } from "blockly";
import xml2json, { json2xml } from "./Blockly/xml2json";
import BlocklyToolbox, { RegisterBlocks } from "./Blockly/toolbox";
RegisterBlocks(Blockly);

export default {
  components: {
    ProseMirrorEditor,
  },
  name: "lpd-form-editorjs",
  props: {
    inputProps: Object,
    group: String,
    initValue: Object | Array | String,
    formId: String
  },
  data: () => ({
    options: {
      mode: "editor",
      blockly: {
        mode: "editor"
      }
    },
    json: { translations: {}, queries: {} },
    content: null,
    codeEditor: null,
    blockEditor: null,
    blockEditorCode: null
  }),
  computed: {
    transProps: function() {
      return {
        label: "Translations",
        help:
          "Use a key and propper translations for your language, you can use query param kies with colon syntax (:queries.crud.users.id.1)",
        create_message: "Create a new translation entry"
      };
    },
    editorModes: function() {
      return [
        {
          icon: "code",
          title: "Code",
          mode: "code"
        },
        {
          icon: "edit",
          title: "Editor",
          mode: "editor"
        },
        {
          icon: "remove_red_eye",
          title: "View",
          mode: "view"
        },
        {
          icon: "translate",
          title: "Translations",
          mode: "trans"
        },
        {
          icon: "storage",
          title: "Query Sources",
          mode: "queries"
        }
      ];
    }
  },
  watch: {
    content: {
      deep: true,
      handler() {
        this.json.content = this.content;
        this.codeEditor.set(this.json);
      }
    },
    json: {
      deep: true,
      handler() {
          this.codeEditor.set(this.json);
      }
    },
    options: {
      deep: true,
      handler() {
        const self = this;
        if (this.options.mode === "queries") {
          if (this.options.blockly.mode === "code") {
            const dom = Blockly.Xml.workspaceToDom(self.blockEditor);
            self.blockEditorCode = Blockly.Xml.domToPrettyText(dom);
          } else {
            self.blockEditor.render();
          }
        }
      }
    }
  },
  methods: {
    setMode: function(mode) {
      this.options.mode = mode;
      if (this.options.mode === "queries") {
        this.options.blockly.mode = "editor";
      }
    },
    toggleBlocklyMode() {
      if (this.options.blockly.mode === "editor") {
        this.options.blockly.mode = "code";
      } else {
        this.options.blockly.mode = "editor";
      }
    },
    downloadWorkspace() {
      const dataStr =
        "data:text/json;charset=utf-8," +
        encodeURIComponent(JSON.stringify(this.json));
      const downloadAnchorNode = document.createElement("a");
      downloadAnchorNode.setAttribute("href", dataStr);
      downloadAnchorNode.setAttribute("download", "workspace.json");
      document.body.appendChild(downloadAnchorNode); // required for firefox
      downloadAnchorNode.click();
      downloadAnchorNode.remove();
    },
    importWorkspace() {
      const downloadAnchorNode = document.createElement("input");
      downloadAnchorNode.setAttribute("type", "file");
      document.body.appendChild(downloadAnchorNode); // required for firefox
      downloadAnchorNode.click();
      const self = this;
      downloadAnchorNode.addEventListener(
        "change",
        function(event) {
          const fileToRead = event.target;
          const files = fileToRead.files;
          if (files.length) {
            const file = files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
              try {
                const json = JSON.parse(e.target.result);
                self.json = json;
                self.content = {...self.json.content};

                const xml = json2xml(self.json.queries, "");

                const dom = Blockly.Xml.textToDom(xml);
                Blockly.Xml.clearWorkspaceAndLoadFromXml(dom, self.blockEditor);
                self.blockEditorCode = Blockly.Xml.domToPrettyText(dom);

              } catch (e) {
                console.error(e);
              }
            };
            reader.readAsText(file);
          }
        },
        false
      );
      downloadAnchorNode.remove();
    }
  },
  mounted: function() {
    const component = this;
    const container = this.$refs.codeEditor;
    const options = {
      mode: "code"
    };
    this.json = this.initValue
      ? typeof this.initValue === "string"
        ? JSON.parse(this.initValue)
        : this.initValue
      : { translations: {}, queries: {} };
    if (container) {
      this.codeEditor = new JSONEditor(container, options);
      this.codeEditor.set(this.json);
    }
    const blockContainer = this.$refs.blockEditor;
    if (blockContainer) {
      this.blockEditor = Blockly.inject(blockContainer, {
        toolbox: BlocklyToolbox
      });
      const self = this;
      this.blockEditor.addChangeListener(function() {
        const dom = Blockly.Xml.workspaceToDom(self.blockEditor);
        self.json.queries = JSON.parse(xml2json(dom, ""));
      });
    }

    this.content = this.json.content ? this.json.content : null;
  },
  beforeDestroy() {
    this.editor.destroy();
  }
};
</script>

<style scoped>
.editor__content {
  min-height: 400px;
  border: solid 1px rgb(188, 188, 188);
}
.editor-menu-bar {
  height: 28px !important;
  min-height: 28px;
  border: solid 1px rgb(188, 188, 188);
}
</style>