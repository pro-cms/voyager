import { createApp } from 'vue';
import Voyager from '../components/Voyager.vue';

const voyager = createApp();
voyager.mount(Voyager, '#voyager');
window.voyager = voyager;

//require('./vendor');
//require('./helper');
//require('./notify');
/*require('./bread');*/
//require('./multilanguage');
/*require('./formfields');*/
//require('./layout');
//require('./ui');
/*
Vue.component('settings-manager', require('../components/Settings/Manager').default);
Vue.component('plugins-manager', require('../components/Plugins/Manager').default);
Vue.component('login', require('../components/Auth/Login').default);

*/