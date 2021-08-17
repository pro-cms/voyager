import EventBus from '@/eventbus';
import Store from '@/store';

export default {
    install(app) {
        app.config.globalProperties.toggleDirection = function () {
            Store.rtl = !Store.rtl;
            if (Store.rtl) {
                document.querySelector('html').setAttribute('dir', 'rtl');
            } else {
                document.querySelector('html').setAttribute('dir', 'ltr');
            }
        };

        // Dark mode
        app.config.globalProperties.toggleDarkMode = function () {
            if (Store.darkmode == 'light') {
                Store.darkmode = 'dark';
            } else if (Store.darkmode == 'dark') {
                Store.darkmode = 'system';
                this.setDarkMode(Store.systemDarkmode ? 'dark' : 'light');
            } else {
                Store.darkmode = 'light';
            }
            localStorage.mode = Store.darkmode;
            if (['dark', 'light'].includes(Store.darkmode)) {
                this.setDarkMode(Store.darkmode);
            }
        };

        app.config.globalProperties.setDarkMode = function (mode) {
            if (mode == 'dark') {
                document.documentElement.classList.add('dark')
            } else if (mode == 'light') {
                document.documentElement.classList.remove('dark')
            }
            Store.darkmode == mode;
        };

        app.config.globalProperties.initDarkMode = function () {
            if (('mode' in localStorage) && ['dark', 'light'].includes(localStorage.mode)) {
                this.setDarkMode(localStorage.mode);
                Store.darkmode = localStorage.mode;
            } else {
                localStorage.mode = 'system';
            }
    
            //systemDarkmode
            var match = window.matchMedia('(prefers-color-scheme: dark)');
            match.addListener(() => {
                Store.systemDarkmode = match.matches;
                if (Store.darkmode == 'system') {
                    match.matches ? this.setDarkMode('dark') : this.setDarkMode('light');
                }
            });
            Store.systemDarkmode = match.matches;
            if (Store.darkmode == 'system') {
                match.matches ? this.setDarkMode('dark') : this.setDarkMode('light');
            }
        };

        // Sidebar
        app.config.globalProperties.toggleSidebar = function () {
            Store.sidebarOpen = !Store.sidebarOpen;
            EventBus.emit('sidebar-open', Store.sidebarOpen);
        };
        app.config.globalProperties.openSidebar = function () {
            Store.sidebarOpen = true;
            EventBus.emit('sidebar-open', true);
        };
        app.config.globalProperties.closeSidebar = function () {
            Store.sidebarOpen = false;
            EventBus.emit('sidebar-open', false);
        };

        // Formfield
        app.config.globalProperties.getFormfieldByType = function (type) {
            var formfield = Store.formfields.where('type', type).first();
            if (!formfield) {
                console.error('Formfield with type "'+type+'" does not exist!');
            }

            return formfield || {};
        };

        // Initialize darkmode
        app.config.globalProperties.initDarkMode();
    }
};