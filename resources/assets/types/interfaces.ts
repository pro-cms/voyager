import * as Vue from 'vue';

interface Voyager extends Vue.App {
    addToUI: (title: string, component: Object) => void;
}

interface WindowEx extends Window {
    slugify: Function;
    Vue: any;
    axios: any;
    Status: Object;
    voyager: Vue.App;
    scrollTo: any;
    debounce: any;
    createVoyager: (data: Data, el: string) => void;
    mountVoyager: () => void;
}

interface Message {
    message: string;
    color: string;
    timeout?: number;
}

interface StoreData {
    darkmode: string;
    systemDarkmode: boolean;
    breads: Array<Object>;
    formfields: Array<Object>;
    tables: Array<string>;
    localization: Array<any>;
    locales: Array<string>;
    locale: string;
    initialLocale: string;
    ui: Array<{ title: string; component: Object }>;
    pageLoading: boolean;
    sidebarOpen: boolean;
    titleSuffix: String;
    rtl: boolean;
    csrfToken: String;
    version: String;
    notificationPosition: String;
    jsonOutput: boolean;
    devServer: { url: String|null; available: boolean; wanted: boolean; };
    sidebar: { items: Array<any>; title: String; iconSize: Number; };
    user: { name: String; avatar: String; items: Array<Object> };
}

interface Data {
    messages: Array<Message>;
}

export { Voyager, WindowEx, Message, StoreData, Data };