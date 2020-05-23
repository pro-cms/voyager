import Router from 'vue-router';
import PageNotFound from '../components/Layout/PageNotFound';
import Dashboard from '../components/Layout/Dashboard';
import Settings from '../components/Settings/Manager';
import BreadBuilderBrowse from '../components/Builder/Browse';
import UI from '../components/Layout/UI';

import store from './store';

const routes = [
    {
        path: '/',
        component: Dashboard,
        meta: {
            title: 'Dashboard',
        }
    },
    {
        path: '/breads',
        component: BreadBuilderBrowse,
        meta: {
            title: 'BREADs',
        },
    },
    {
        path: '/media',
        component: UI,
        meta: {
            title: 'Media',
        }
    },
    {
        path: '/ui',
        component: UI,
        meta: {
            title: 'UI',
        }
    },
    {
        path: '/settings',
        component: Settings,
        meta: {
            title: 'Settings',
        }
    },
    {
        path: '/plugins',
        component: UI,
        meta: {
            title: 'Plugins',
        }
    },
    {
        path: '*',
        component: PageNotFound,
        meta: {
            title: '404',
        }
    },
];

const router = new Router({
    routes,
});

router.beforeEach(function (to, from, next) {
    var suffix = 'Voyager II';
    if (to.meta.hasOwnProperty('title')) {
        document.title = to.meta.title + ' - ' + suffix;
    } else {
        document.title = suffix;
    }

    next();
});

// Dynamically generate routes for BREADs

export default router;