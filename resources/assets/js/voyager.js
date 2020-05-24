require('./vendor');
require('./helper');
require('./formfields');
require('./ui');

import Vue from 'vue';

import Voyager from '../components/Voyager';
Vue.component('voyager', Voyager);

import Login from '../components/Auth/Login';
Vue.component('login', Login);

import Store from './store';
Vue.use(Store);

import Language from './multilanguage';
Vue.use(Language);

import Notify from './notify';
Vue.use(Notify);