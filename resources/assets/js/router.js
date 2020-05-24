import Router from 'vue-router';
import Dashboard from '../components/Layout/Dashboard';
import Settings from '../components/Settings/Manager';
import BreadBuilderBrowse from '../components/Builder/Browse';
import MediaManager from '../components/Media/Manager';
import PluginManager from '../components/Plugins/Manager';
import UI from '../components/Layout/UI';

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
        component: MediaManager,
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
        component: PluginManager,
        meta: {
            title: 'Plugins',
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

export default router;