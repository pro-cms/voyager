<template>
    <slide-x-left-transition class="h-screen flex overflow-hidden" id="voyager" tag="div" group>
        <div key="loader">
            <fade-transition :duration="500">
                <div class="loader" v-if="store.pageLoading">
                    <icon icon="helm" size="auto" class="block icon rotating-cw"></icon>
                </div>
            </fade-transition>
        </div>
        <sidebar key="sidebar" />
        <div class="flex flex-col w-0 flex-1 overflow-hidden" key="content">
            <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 outline-none">
                <span id="top"></span>
                <nav class="h-16 flex justify-start mb-3 mx-auto sm:px-3 md:px-4">
                    <div class="flex justify-between items-center w-full">
                        <button @click.stop="store.toggleSidebar" class="button dark-gray small icon-only mx-2">
                            <icon :icon="store.sidebarOpen ? 'ellipsis-v' : 'ellipsis-h'" />
                        </button>
                        <div class="w-full mt-4">
                            <search placeholder="XYZ" mobile-placeholder="ABC" />
                        </div>
                        <user-dropdown />
                    </div>
                </nav>
                <div class="mx-auto sm:px-3 md:px-4" id="top">
                    <collapse-transition>
                        <router-view :key="$route.fullPath" />
                    </collapse-transition>
                </div>
            </main>
        </div>
        <notifications key="notifications"></notifications>
    </slide-x-left-transition>
</template>
<script>
import Sidebar from './Layout/Sidebar';
import BreadBuilderEditAdd from '../components/Builder/EditAdd';

import router from '../js/router';
import store from '../js/store';

export default {
    router,
    components: { Sidebar },
    data: function () {
        return {
            store: store
        };
    },
    methods: {
        generateRoutesForBreads: function () {
            var vm = this;
            var routes = [];
            vm.store.tables.forEach(function (table) {
                var bread = vm.store.getBreadByTable(table);
                if (bread) {
                    routes.push({
                        path: '/bread/' + table,
                        name: 'builder-' + table,
                        component: BreadBuilderEditAdd,
                        props: { table: table },
                        meta: {
                            title: 'Edit BREAD',
                        }
                    });
                    routes.push({
                        path: '/'+vm.translate(bread.slug, true),
                        component: BreadBuilderEditAdd,
                        meta: {
                            title: vm.translate(bread.name_plural, true),
                        }
                    });
                } else {
                    routes.push({
                        path: '/bread/' + table,
                        component: BreadBuilderEditAdd,
                        props: { table: table },
                        meta: {
                            title: 'Add BREAD',
                        }
                    });
                }
            });
            router.addRoutes(routes);
        }
    },
    mounted: function () {
        var vm = this;

        axios.post(document.head.querySelector('meta[name="base-url"]').content)
        .then(function (response) {
            for (var key in response.data) {
                if (response.data.hasOwnProperty(key)) {
                    if (key == 'localization') {
                        vm.$language.localization = response.data[key];
                    } else {
                        vm.store[key] = response.data[key];
                    }
                }
            }
            vm.generateRoutesForBreads();
        }).catch(function (error) {
            //
        }).then(function () {
            vm.store.pageLoading = false;
        });
    },
    created: function () {
        var dark_mode = this.getCookie('dark-mode');
        if (dark_mode == 'true') {
            this.store.toggleDarkMode();
        }

        var sidebar_open = this.getCookie('sidebar-open');
        if (sidebar_open == 'false') {
            this.store.toggleSidebar();
        }
    },
    watch: {
        'store.sidebarOpen': function (open) {
            this.setCookie('sidebar-open', (open ? 'true' : 'false'), 360);
        },
        'store.darkmode': function (darkmode) {
            this.setCookie('dark-mode', (darkmode ? 'true' : 'false'), 360);
        },
        'store.sidebarOpen': function (open) {
            this.setCookie('sidebar-open', (open ? 'true' : 'false'), 360);
        },
    }
}
</script>