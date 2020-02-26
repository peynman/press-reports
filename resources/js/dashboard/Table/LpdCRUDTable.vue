<template>
  <div>
	  <v-alert
          v-model="alert.show"
          :type="alert.type"
          :color="alert.color"
          dense
          transition="slide-y-transition"
          dismissible
          class="ma-4"
        >{{ alert.message }}</v-alert>
    <v-card-title>
      <v-tooltip top v-for="(link, i) in links" :key="'table-'+metadata.resource.name+'-link-'+i">
        <template v-slot:activator="{on}">
          <v-btn
            text
            icon
			      small
            v-on="on"
            @click="link.onClick"
            :color="link.color()"
            :loading="link.loading"
          >
            <v-icon>{{ link.icon() }}</v-icon>
          </v-btn>
        </template>
        <span>{{ link.title }}</span>
      </v-tooltip>
      <span>
        {{ title }}
        <v-tooltip top v-if="hasTranslatable">
          <template v-slot:activator="{ on }">
            <v-btn
              small
              dense
              tile
              outlined
              v-on="on"
              class="translatable-header flex-row"
              color="secondary"
              @click="nextLang()"
            >
              {{ tableLang.abbr }}
              <v-icon small>chevron_right</v-icon>
            </v-btn>
          </template>
          <span>{{ tableLang.title }}</span>
        </v-tooltip>
      </span>
      <v-spacer></v-spacer>
	  <v-text-field
        v-model="search"
        append-icon="search"
        :label="getMetadataVerbString(translations.tables.actions.search, metadata)"
        single-line
        hide-details
		class="ma-0 pa-0"
      ></v-text-field>
    </v-card-title>
    <v-divider></v-divider>
    <div>
      <lpd-crud-table-form
        :form-props="settingsProps"
        :show-form="showLink === 'settings'"
        :form-id="metadata.resource.name+'-settings'"
      />
      <lpd-crud-table-form
        :form-props="filterProps"
        :show-form="showLink === 'filters'"
        :form-id="metadata.resource.name+'-filters'"
      />
      <lpd-crud-table-form
        :form-props="createProps"
        :show-form="showLink === 'create'"
        :form-id="metadata.resource.name+'-create'"
        @new-tab="newTab"
      />
      <v-data-table
        :headers="headers"
        :items="items"
        :search="search"
        :expanded.sync="expanded"
        :server-items-length="total"
        :options.sync="options"
        :sort-by.sync="sortBy"
        :sort-desc.sync="sortDesc"
        :loading="loading"
        :calculate-widths="true"
        single-expand
        multi-sort
        @click:row="onToggleItem"
      >
        <template v-for="template in templates" v-slot:[getTemplateSlot(template)]="{item}">
          <component
            :is="template.component"
            :key="template.column"
            :item="item"
            :params="template.params"
            :column="template.column"
            :extras="template.extras"
            @new-tab="newTab"
            @open-extend="onExpandItem"
            @close-extend="onCloseItem"
          />
        </template>

        <template v-for="header in headers" v-slot:[getHeaderSlot(header)]="{}">{{ header.text }}</template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
import LpdCrudTableForm from "./LpdCRUDTableForm";
import { mapState } from "vuex";

export default {
  components: {
    LpdCrudTableForm
  },
  name: "lpd-crud-table",
  props: {
    metadata: Object
  },
  computed: {
    headers: function() {
      const headers = [];
      if (this.metadata.table.columns) {
        this.metadata.table.columns.forEach(h => {
          if (
            h.type !== "template" ||
            !h.template.params ||
            h.template.params.header !== false
          ) {
            headers.push({
              text: h.title,
              value: h.column,
              align: "start",
              sortable: h.sortable,
              divider: true,
              width: h.width
            });
          }
        });
      }
      return headers;
    },
    templates: function() {
      const templates = [];
      if (this.metadata.table.columns) {
        this.metadata.table.columns.forEach(h => {
          if (h.type === "template") {
            let extras = {};
            switch (h.template.component) {
              case "datetime":
                extras["created_at_format"] = "";
                break;
              case "translatable":
                extras["currentLang"] = this.tableLang;
                this.hasTranslatable = true;
                break;
            }

            const template = {
              component: "lpd-crud-table-column-" + h.template.component,
              params: h.template.params,
              column: h.column,
              extras: extras
            };
            templates.push(template);
            if (h.template.params.slot === "expanded-item") {
              this.expandTemplate = template;
              this.expandTemplateDefaultMetadata = {
                ...template.params.metadata
              };
            }
          }
        });
      }

      return templates;
    },
    alert: function() {
      if (!this.response) {
        return {
          show: false
        };
      }

      return {
        show: this.response.status !== 200,
        color: this.response.status === 200 ? "blue-grey darken-1 " : "error",
        type: this.response.status !== 200 ? "error" : "success",
        message: this.response.data.message,
        messages: this.response.data.errors
      };
    },
    title: function() {
      return this.metadata.resource.title.plural;
    },
    createProps: function() {
      return {
        fields: this.metadata.form.create.fields,
        options: this.metadata.form.create.options,
        validations: this.metadata.form.create.validations,
        actions: this.metadata.form.actions,
        action: this.metadata.resource.urls.create.post,
        width: 12,
        dense: true,
        small: true,
        openNewTab: {
          title: this.getMetadataVerbString(
            this.translations.tables.actions.create,
            this.metadata
          ),
          url: this.metadata.resource.urls.create.view
        }
      };
    },
    filterProps: function() {
      return {
        fields: this.metadata.table.filters.fields,
        validations: this.metadata.table.filters.validations,
        actions: [],
        action: this.metadata.resource.urls.filters.post,
        width: 12,
        mode: "simple"
      };
    },
    settingsProps: function() {
      const fields = [];

      const datetime_formats = this.datetimeFormats;
      const column_flags = [];
      this.headers.forEach(h => {
        column_flags.push({
          id: h.value,
          title: h.text
        });
      });
      fields.push({
        id: "created_at_format",
        label: this.translations.forms.labels.created_at_format,
        input: "select",
        objects: datetime_formats,
        group: "dates",
        col: "col-lg-4 col-sm-6",
        decorator: {
          id: "id",
          title: "title",
          label: ":title"
        }
      });
      fields.push({
        id: "updated_at_format",
        label: this.translations.forms.labels.updated_at_format,
        input: "select",
        objects: datetime_formats,
        group: "dates",
        col: "col-lg-4 col-sm-6",
        decorator: {
          id: "id",
          title: "title",
          label: ":title"
        }
      });
      fields.push({
        id: "other_dates_format",
        label: this.translations.forms.labels.other_date_format,
        input: "select",
        objects: datetime_formats,
        group: "dates",
        col: "col-lg-4 col-sm-6",
        decorator: {
          id: "id",
          title: "title",
          label: ":title"
        }
      });
      fields.push({
        id: "hide_columns",
        label: this.translations.forms.labels["hide-columns"],
        input: "object-ids",
        objects: column_flags,
        group: "display",
        col: "col-12",
        decorator: {
          id: "id",
          title: "title",
          label: ":title"
        }
      });

      return {
        fields: fields,
        dense: true,
        small: true,
        onBeforeSubmit: function(form) {
          return {
            session: "settings",
            settings: form.data
          };
        },
        actions: [
          {
            name: "submit",
            label: this.translations.forms.buttons.settings_submit,
            action: this.metadata.resource.urls.filters.post,
            class: "ma-0 pa-0 mt-2",
            props: {
              block: true,
              color: "primary",
              dense: true,
              small: true
            }
          },
          {
            name: "apply",
            label: this.translations.forms.buttons.settings_apply_only,
            class: "ma-0 pa-0 mt-1",
            props: {
              block: true,
              outlined: true,
              color: "success",
              dense: true,
              small: true
            }
          }
        ],
        mode: "simple"
      };
    },
    links: function() {
      const self = this;
      const links = [
        {
          title: this.getMetadataVerbString(
            this.translations.tables.actions.create,
            this.metadata
          ),
          onClick: () => self.showLink = self.showLink === 'create' ? null:'create',
          color: () => (self.showLink === 'create' ? "warning" : "secondary"),
		  icon: () => (self.showLink === 'create' ? "close" : "mdi-plus-box"),
        },
        {
          title: this.getMetadataVerbString(
            this.translations.tables.actions.settings,
            this.metadata
          ),
          onClick: () => (self.showLink = self.showLink === 'settings' ? null:'settings'),
          color: () => (self.showLink === 'settings' ? "warning" : "secondary"),
          icon: () => (self.showLink === 'settings' ? "close" : "mdi-settings")
        },
        {
          title: this.getMetadataVerbString(
            this.translations.tables.actions.filters,
            this.metadata
          ),
          onClick: () => (self.showLink = self.showLink === 'filters' ? null:'filters'),
          color: () => (self.showLink === 'filters' ? "warning" : "secondary"),
          icon: () => (self.showLink === 'filters' ? "close" : "filter_list")
        },
        {
          title: this.getMetadataVerbString(
            this.translations.tables.actions.refresh,
            this.metadata
          ),
          onClick: this.updateTable,
          color: () => (self.loading ? "warning" : "secondary"),
          icon: () => "refresh",
          loading: self.loading
        }
      ];

      return links;
    },
    ...mapState({
      translations: state => state.config.language.translations,
      languages: state => state.config.language.available,
      currentLang: state =>
        state.config.language.available[
          state.config.language.available
            .map(m => m.id)
            .indexOf(state.page.options.values.language)
        ],
      tableLang: function(state) {
        if (this.currLang) {
          return this.currLang;
        }

        return this.currentLang;
      },
      datetimeFormats: function(state) {
        const datetime_formats = [];
        state.config.datetime.modes.forEach(mode => {
          datetime_formats.push({
            id: mode,
            title: mode
          });
        });
        return datetime_formats;
      }
    })
  },
  data: () => ({
    expanded: [],
    search: "",
    items: [],
    showLink: false,
    showAlert: false,
    loading: false,
    options: {},
    total: 0,
    response: null,
    sortBy: null,
    sortDesc: null,
    loadingId: 0,
    expandTemplate: null,
    expandTemplateDefaultMetadata: null,
    currLang: null,
    hasTranslatable: false
  }),
  watch: {
    search: function(o, n) {
      this.updateTable();
    },
    options: {
      deep: true,
      handler() {
        this.updateTable();
      }
    },
    currentLang: function(n) {
      this.currLang = null;
    }
  },
  methods: {
    updateTable() {
      this.loading = true;
      this.loadingId += 1;
      const sort = [];

      this.options.sortBy.forEach((s, index) => {
        sort.push({
          column: s,
          direction: this.options.sortDesc[index] ? "desc" : "asc"
        });
      });

      axios
        .post(this.metadata.resource.urls.query.url, {
          ref_id: this.loadingId,
          page: this.options.page,
          limit: this.options.itemsPerPage,
          search: this.search,
          sort: sort,
          with: this.metadata.resource.urls.query.params.with
        })
        .then(response => {
          this.response = response;
          if (response.data.data && this.loadingId <= response.data.ref_id) {
            this.updateTableData(response.data);
          }
        })
        .catch(error => {
          this.response = error.response;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    updateTableData(paginated) {
      this.items = paginated.data;
      this.total = paginated.total;
    },
    getTemplateSlot(template) {
      if (template.params && template.params.slot) {
        return template.params.slot;
      }

      return "item." + template.column;
    },
    getHeaderSlot(header) {
      return "header." + header.value;
    },
    onToggleItem(item) {
      this.toggleArray(this.expanded, item);
      this.expandTemplate.params.metadata = this.expandTemplateDefaultMetadata;
    },
    onCloseItem(target) {
      this.putOffArray(this.expanded, target.item);
    },
    onExpandItem(target) {
      this.putOnArray(this.expanded, target.item);
      this.expandTemplate.params.metadata = target.link.metadata;
    },
    newTab(link, data) {
      console.log("table new tab", link, data);
      this.$emit("new-tab", link, data);
    },
    nextLang() {
      const curr = this.languages.map(l => l.id).indexOf(this.tableLang.id);
      this.currLang = this.languages[
        curr === this.languages.length - 1 ? 0 : curr + 1
      ];
    }
  }
};
</script>

<style scoped>
.translatable-header {
  width: 40px;
  max-width: 40px !important;
  min-width: 40px !important;
  height: 16px !important;
  padding: 0 !important;
  font-size: 10px !important;
}
.translatable-header .v-icon {
  font-size: 12px !important;
}
</style>