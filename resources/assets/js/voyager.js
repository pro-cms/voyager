require('./vendor');
require('./notify');
require('./helper');
require('./multilanguage');
require('./formfields');
require('./ui');

Vue.component('settings-manager', require('../components/Settings/Manager').default);
Vue.component('plugins-manager', require('../components/Plugins/Manager').default);
Vue.component('login', require('../components/Auth/Login').default);

import Vue from 'vue';
import Voyager from '../components/Voyager';

new Vue({
    el: '#voyager',
    components: { Voyager },
    template: '<Voyager/>'
  })
  
