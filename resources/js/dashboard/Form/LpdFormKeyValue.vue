<template>
  <div class="d-flex flex-column">
    <div class="d-flex flex-row align-center mb-1">
      <v-tooltip top>
        <template v-slot:activator="{on}">
          <v-btn icon text small dense v-on="on" class="green" dark @click="addNewEntry()">
            <v-icon small>add</v-icon>
          </v-btn>
        </template>
        {{inputProps.create_message}}
      </v-tooltip>
      <label :class="'ms-2 ' + (inputProps.error ? 'red--text me-1':'me-1')">{{ inputProps.label }}</label>
    </div>
    <v-expansion-panels accordion flat v-model="openPanels" class="mt-2">
      <v-expansion-panel v-for="(val, index) in values" :key="`${formId}-key-${index}`">
        <v-expansion-panel-header>
          <template v-slot:default="{ open }">
            <div class="d-flex justify-space-between align-center">
              <span class="col-8 text--secondary ma-0 pa-0">
                <v-text-field v-model="val.key" solo flat small dense hide-details required></v-text-field>
              </span>
              <div class="d-flex justify-end align-center" v-if="! open"></div>
              <div class="d-flex justify-end align-center" @click.stop>
                <v-btn v-if="open" class="me-1" text small dense icon color="warning" @click="removeItem(index)">
                  <v-icon>cancel</v-icon>
                </v-btn>
              </div>
            </div>
          </template>
        </v-expansion-panel-header>
        <v-expansion-panel-content>
          <lpd-form-translatable-text-input
            :form-id="formId"
            v-model="val.trans"
            :input-props="{id:val.key, label:''}"
            :init-value="val.trans ? val.trans.translations:{}"
            class="me-2"
            small
            dense
          ></lpd-form-translatable-text-input>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
    <v-subheader
      v-if="inputProps.help"
      :class="inputProps.error ? 'red--text me-1':'me-1'"
    >{{ inputProps.help }}</v-subheader>
  </div>
</template>


<script>
export default {
  name: "lpd-form-key-value",
  components: {},
  props: {
    inputProps: Object,
    group: String,
    initValue: Object | Array | String,
    formId: String
  },
  computed: {},
  data: () => ({
    openPanels: [],
    values: []
  }),
  watch: {
    values: {
      deep: true,
      handler() {
        this.$emit("input", this.values);
      }
    }
  },

  methods: {
    addNewEntry: function() {
      const key = "my.sample.trans." + this.getRandomString(3);
      this.values.push({
        key: key
      });
    },
    onTextFieldFocus: function(index) {
      this.openPanels = [index];

      return false;
    },
    removeItem(index) {
        this.openPanels = [];
        this.values.splice(index, 1);
    },
  },
  created: function() {
    if (this.initValue) {
      if (typeof this.initValue !== "array") {
        this.values = [];
        for (let prop in this.initValue) {
          if (this.initValue.hasOwnProperty(prop)) {
            this.values.push({
              key: prop,
              trans: {},
              ...this.initValue[prop]
            });
          }
        }
      } else {
        this.values = this.initValue;
      }
    }
  }
};
</script>

<style scoped>
.v-expansion-panel::before {
  box-shadow: 0px 0px 0px 0px rgba(0, 0, 0, 0.2),
    0px 0px 0px 0px rgba(0, 0, 0, 0.14), 0px 0px 0px 0px rgba(0, 0, 0, 0.12) !important;
}
.v-expansion-panel-header {
  padding: 2px 24px;
}
.v-expansion-panel-header .v-text-field {
  padding: 0;
  margin: 0;
}
.v-expansion-panel-header .v-text-field.v-input--dense .v-text-field__details {
  display: none !important;
}
.v-expansion-panel-header .v-text-field.v-input--dense  .v-input__slot {
  margin: 0 !important;
}
.v-expansion-panel-header .v-text-field.v-input--dense input {
  margin: 0 !important;
  padding: 0 !important;
}
.v-expansion-panel-content .v-expansion-panel-content__wrap {
  padding: 2px 24px;
}
</style>