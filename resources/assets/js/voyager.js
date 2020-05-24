require('./vendor');
require('./notify');
require('./helper');
require('./multilanguage');
require('./formfields');
require('./ui');

import Vue from 'vue';

import Voyager from '../components/Voyager';
Vue.component('voyager', Voyager);

import Login from '../components/Auth/Login';
Vue.component('login', Login);

import Store from './store';
Vue.use(Store);