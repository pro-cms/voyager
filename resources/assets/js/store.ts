import { reactive } from 'vue';
import { StoreData } from '../types/interfaces';

export default reactive<StoreData>({
    darkmode: 'system',
    systemDarkmode: false,

    breads: [],
    formfields: [],

    localization: [],
    locales: [],
    locale: '',
    initialLocale: '',

    ui: [],

    pageLoading: true,
    sidebarOpen: true,
    titleSuffix: '',
    rtl: false,
    csrfToken: '',

    version: '',

    notificationPosition: 'top-right',

    jsonOutput: false,
    devServer: {
        url: null,
        available: false,
        wanted: false,
    },

    sidebar: {
        items: [],
        title: '',
        iconSize: 6,
    },

    user: {
        name: '',
        avatar: '',
    }
});