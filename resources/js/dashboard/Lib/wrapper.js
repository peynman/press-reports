import LpdContainer from "../Partials/LpdContainer";
import LpdContent from "../Partials/LpdContent";
import LpdAppBar from "../Partials/LpdAppBar";
import LpdSideMenu from "../Partials/LpdSideMenu";
import LpdOptionsMenu from "../Partials/LpdOptionsMenu";
import LpdMenuItemSingle from '../Menu/LpdMenuItemSingle';
import LpdMenuItemAccordion from '../Menu/LpdMenuItemAccordion';
import LpdMenuItemHeader from '../Menu/LpdMenuItemHeader';
import LpdCRUDBrowse from '../Pages/LpdCRUDBrowse'
import LpdCRUDBrowseTab from '../Pages/LpdCRUDBrowseTab'
import LpdCRUDCreate from '../Pages/LpdCRUDCreate'
import LpdCRUDUpdate from '../Pages/LpdCRUDUpdate'

export const dashboardComponents =  {
    'lpd-content': LpdContent,
    'lpd-container': LpdContainer,
    'lpd-app-bar': LpdAppBar,
    'lpd-side-menu': LpdSideMenu,
    'lpd-options-menu': LpdOptionsMenu,
    'lpd-menu-item-single': LpdMenuItemSingle,
    'lpd-menu-item-accordion': LpdMenuItemAccordion,
    'lpd-menu-item-header': LpdMenuItemHeader,
    'lpd-crud-browse': LpdCRUDBrowse,
    'lpd-crud-browse-tab': LpdCRUDBrowseTab,
    'lpd-crud-create': LpdCRUDCreate,
    'lpd-crud-update': LpdCRUDUpdate,
};

import LpdCRUDTableColumnOptions from '../Table/LpdCRUDTableColumnOptions'
import LpdCRUDTable from '../Table/LpdCRUDTable'
import LpdCRUDTableColumnObjectLinks from '../Table/LpdCRUDTableColumnObjectLinks'
import LpdCRUDTableColumnDatetime from '../Table/LpdCRUDTableColumnDatetime'
import LpdCRUDTableColumnQuickView from '../Table/LpdCRUDTableColumnQuickView'
import LpdCRUDTableColumnObjectFlags from '../Table/LpdCRUDTableColumnObjectFlags'
import LpdCRUDTableColumnTranslatable from '../Table/LpdCRUDTableColumnTranslatable'
import LpdCRUDTableColumnHoverList from '../Table/LpdCRUDTableColumnHoverList'
import LpdCRUDTableColumnObjectsList from '../Table/LpdCRUDTableColumnObjectsList'

export const tableComponents = {
    'lpd-crud-table': LpdCRUDTable,
    'lpd-crud-table-column-options': LpdCRUDTableColumnOptions,
    'lpd-crud-table-column-datetime': LpdCRUDTableColumnDatetime,
    'lpd-crud-table-column-object-links': LpdCRUDTableColumnObjectLinks,
    'lpd-crud-table-column-quick-view' : LpdCRUDTableColumnQuickView,
    'lpd-crud-table-column-object-flags': LpdCRUDTableColumnObjectFlags,
    'lpd-crud-table-column-translatable': LpdCRUDTableColumnTranslatable,
    'lpd-crud-table-column-hover-list': LpdCRUDTableColumnHoverList,
    'lpd-crud-table-column-objects-list': LpdCRUDTableColumnObjectsList,
};

import LpdFormTextInput from '../Form/LpdFormTextInput'
import LpdFormObjectIds from '../Form/LpdFormObjectIds'
import LpdCRUDForm from '../Form/LpdCRUDForm'
import LpdFormJSON from '../Form/LpdFormJSON'
import LpdFormFlags from '../Form/LpdFormFlags'
import LpdFormInlineTable from '../Form/LpdFormInlineTable'
import LpdFormTranslatableTextInput from '../Form/LpdFormTranslatableTextInput'
import LpdFormSelect from '../Form/LpdFormSelect'
import LpdFormDatetime from '../Form/LpdFormDatetime'
import LpdFormEditorJS from '../Form/LpdFormEditorJS'
import LpdFormKeyValue from '../Form/LpdFormKeyValue'

export const formComponents = {
    "lpd-crud-form": LpdCRUDForm,
    "lpd-form-text": LpdFormTextInput,
    "lpd-form-object-ids": LpdFormObjectIds,
    "lpd-form-json": LpdFormJSON,
    "lpd-form-flags": LpdFormFlags,
    "lpd-form-inline-table": LpdFormInlineTable,
    "lpd-form-translatable-text-input": LpdFormTranslatableTextInput,
    "lpd-form-select": LpdFormSelect,
    "lpd-form-datetime": LpdFormDatetime,
    "lpd-form-editorjs": LpdFormEditorJS,
    "lpd-form-key-value": LpdFormKeyValue,
};

export default {
    install(Vue, options) {
        const register = function (Vue, components) {
            for (let comp in components) {
                if (components.hasOwnProperty(comp)) {
                    Vue.component(comp, components[comp])
                }
            }
        };

        register(Vue, dashboardComponents);
        register(Vue, formComponents);
        register(Vue, tableComponents);
    },

    LpdCRUDForm,
    LpdFormTextInput,
    LpdFormObjectIds,
    LpdFormJSON,
    LpdFormInlineTable,
    LpdFormTranslatableTextInput,
    LpdFormSelect,
    LpdFormDatetime,
    LpdFormKeyValue,

    LpdAppBar,
    LpdContent,
    LpdContainer,
    LpdMenuItemHeader,
    LpdMenuItemAccordion,
    LpdMenuItemSingle,
    LpdOptionsMenu,
    LpdSideMenu,
    LpdCRUDBrowseTab,
    LpdCRUDCreate,
    LpdCRUDUpdate,

    LpdCRUDTable,
    LpdCRUDTableColumnOptions,
    LpdCRUDTableColumnObjectLinks,
    LpdCRUDTableColumnDatetime,
    LpdCRUDTableColumnQuickView,
    LpdCRUDTableColumnObjectFlags,
    LpdCRUDTableColumnTranslatable,
    LpdCRUDTableColumnHoverList,
    LpdCRUDTableColumnObjectsList,
}