<template>
  <v-app>
    <lpd-app-bar />
    <lpd-side-menu />
    <lpd-options-menu />
    <lpd-content />
  </v-app>
</template>

<script>
import Vue from "vue";
import Vuetify from "vuetify";
import Vuex, { mapMutations } from "vuex";
import VuetifyLib from "vuetify/lib";
import VueAxios from "vue-axios";
import axios from "axios";
import moment from "moment";
import momentTz from "moment-timezone";

import DashboardComponents from "./Lib/wrapper";
import mutations from "./Lib/mutations";

Vue.use(Vuetify);
Vue.use(Vuex);
Vue.use(VueAxios, axios);
Vue.use(DashboardComponents);

import VuexPersist from "vuex-persist";
const vuexPersist = new VuexPersist({
  key: "larapress-dashboard",
  storage: window.localStorage
});

const store = new Vuex.Store({
  state: {
    storage: {},
    config: {
      rtl: window.AppConfig.language.rtl,
      language: window.AppConfig.language,
      datetime: {
        modes: [
          "Y-M-D hh:mm:ss",
          "Y-M-D hh:mm",
          "Y-M-D hh:mm a",
          "Y-M-D",
          "h:mm a",
          "hh:mm:ss",
          "MMM Do Y hh:mm",
          "MMM Do Y hh:mm a",
          "MMM Do Y"
        ],
        calendars: {
          gregorian: moment,
          jalali: moment,
          hejri: moment
        }
      }
    },
    page: {
      title: window.AppConfig.page.title,
      user: window.AppConfig.page.user,
      sidebar: {
        visible: false,
        items: window.AppConfig.sidebar.items
      },
      options: {
        visible: false,
        defaults: {
          calendar: "gregorian",
          timezone: "utc",
          updated_at_format: "Y-M-D hh:mm",
          created_at_format: "MMM Do Y",
          other_dates_format: "Y-M-D hh:mm:ss",
          language: "en"
        },
        values: {
          calendar: "gregorian",
          timezone: "utc",
          updated_at_format: "Y-M-D hh:mm:ss",
          created_at_format: "Y-M-D hh:mm:ss",
          other_dates_format: "Y-M-D hh:mm:ss",
          language: "en"
        },
        ...window.AppConfig.options
      },
      content: {
        component: window.AppConfig.content.component,
        props: {
          metadata: window.AppConfig.content.metadata,
          ...window.AppConfig.content.props
        }
      }
    },
    api: {
      jwt: null
    }
  },
  mutations: {
    [mutations.setSideMenuVisible](state, status) {
      state.page.sidebar.visible = status;
    },
    [mutations.setOptionsMenuVisible](state, status) {
      state.page.options.visible = status;
    },
    [mutations.updateJWTToken](state, token) {
      state.api.jwt = token;
      axios.defaults.headers.common["Authorization"] = "Bearer " + token;
    },
    [mutations.setOptions](state, options) {
      state.page.options.values = { ...state.page.options.values, ...options };
    },
    [mutations.setLanguage](state, lang) {
      state.page.options.values.language = lang;
    }
  },
  actions: {},
  getters: {
    isRTL: state => state.config.rtl,
    isSideMenuVisible: state => state.page.sidebar.visible,
    isOptionsMenuVisible: state => state.page.options.visible
  },
  // plugins: [vuexPersist.plugin]
});

Vue.mixin({
  methods: {
    getMenuItemKey(item, index) {
      return (
        item.component +
        item.props.title.replace(/\s+/g, "-").toLowerCase() +
        "-" +
        index
      );
    },
    getRandomString(len) {
      let result = "";
      const characters =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
      let charactersLength = characters.length;
      for (let i = 0; i < len; i++) {
        result += characters.charAt(
          Math.floor(Math.random() * charactersLength)
        );
      }
      return result;
    },
    getDotStr(obj, str) {
      str = str.split(".");
      for (let i = 0; i < str.length; i++) {
        if (!obj[str[i]]) {
          return null;
        }
        obj = obj[str[i]];
      }
      return obj;
    },
    toggleArray(arr, item) {
      const index = arr.indexOf(item);
      if (index >= 0) {
        arr.splice(index, 1);
      } else {
        arr.push(item);
      }
    },
    putOnArray(arr, item) {
      const index = arr.indexOf(item);
      if (index < 0) {
        arr.push(item);
      }
    },
    putOffArray(arr, item) {
      const index = arr.indexOf(item);
      if (index >= 0) {
        arr.splice(index, 1);
      }
    },
    getDateTimeString(datetime, format, mode) {
      return moment(datetime, format).format(mode);
    },
    getFieldValidationsRuleBook(field) {
      const translations = window.AppConfig.language.translations;
      return {
        required: v => {
          if (v) {
            return v.length > 0;
          }
          return translations.validations.required.replace(
            ":attribute",
            field.id
          );
        },
        string: v => {
          if (v) {
            return typeof v === "string"
              ? false
              : ref.translations.validations.string.replace(
                  ":attribute",
                  field.id
                );
          }
          return false;
        },
        ip_list: v => {
          if (v) {
          }

          return false;
        }
      };
    },
    getMetadataVerbString(string, metadata) {
      return string
        .replace(":resource", metadata.resource.title.singular)
        .replace(":resources", metadata.resource.title.plural);
    },
    getDecoratorLabel(decorator, item) {
      return decorator.label
        .replace(":id", item[decorator.id])
        .replace(":title", item[decorator.title]);
    }
  }
});

const vuetify = new VuetifyLib({
  rtl: window.AppConfig.language.rtl,
  theme: {
    options: {
      customProperties: true
    }
  }
});
Vue.config.ignoredElements = [
  "xml",
  "block",
  "category",
  "field",
  "value",
  "mutation"
];

export default {
  name: "lpd-app",
  store,
  vuetify,

  created() {
    const meta = document.querySelector("meta[name='jwt-token']");
    if (meta) {
      const jwtToken = meta.getAttribute("content");
      if (jwtToken) {
        this.updateJWTToken(jwtToken);
      }
    }
  },

  methods: {
    ...mapMutations([mutations.updateJWTToken])
  }
};
</script>