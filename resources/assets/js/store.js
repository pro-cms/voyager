export default {
    formfields: [],
    breads: [],
    backups: [],
    csrf_token: document.head.querySelector('meta[name="csrf-token"]').content,
    debug: true,
    darkmode: false,
    rtl: false,
    sidebarOpen: true,
    plugins: [],
    pageLoading: true,
    routes: [],
    tables: [],
    user: {},
    user_name: '',
    search_title: '',
    settings: [],
    toggleDirection () {
        this.rtl = !this.rtl;
        if (this.rtl) {
            document.querySelector('html').setAttribute('dir', 'rtl');
        } else {
            document.querySelector('html').setAttribute('dir', 'ltr');
        }
    },
    toggleDarkMode () {
        this.darkmode = !this.darkmode;
        if (this.darkmode) {
            document.querySelector('html').classList.add('mode-dark');
        } else {
            document.querySelector('html').classList.remove('mode-dark');
        }
    },
    toggleSidebar () {
        this.sidebarOpen = !this.sidebarOpen;
    },
    openSidebar () {
        this.sidebarOpen = true;
    },
    closeSidebar () {
        this.sidebarOpen = false;
    },
    getFormfieldByType (type) {
        return this.formfields.filter(function (formfield) {
            return formfield.type == type;
        })[0];
    },
    getBreadByTable (table) {
        return this.breads.filter(function (bread) {
            return bread.table == table;
        })[0];
    },
};