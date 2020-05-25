<template>
<div>
    <!-- Mobile sidebar -->
    <div v-if="$store.sidebarOpen" class="md:hidden" :key="'mobile_sidebar'">
        <div class="fixed inset-0 z-30">
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>
        <div class="fixed inset-0 flex z-40" @click="$store.toggleSidebar()">
            <div class="flex-1 flex flex-col max-w-xs w-full sidebar" @click.stop="">
                <div class="absolute top-0 right-0 p-1">
                    <button @click="$store.toggleSidebar()" class="flex items-center justify-center h-12 w-12 rounded-full">
                        <icon icon="times"></icon>
                    </button>
                </div>
                <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                    <div class="flex-shrink-0 flex items-center px-4">
                        <icon icon="helm" :size="10" class="icon"></icon>
                        <span class="font-black text-lg uppercase pl-2 title">
                            Voyager II
                        </span>
                    </div>
                    <nav class="mt-3 px-2">
                    <menu-item
                        :title="__('voyager::generic.dashboard')"
                        href="#"
                        icon="dashboard" 
                        active>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.breads')"
                        href="#"
                        icon="bread" 
                        active>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.media')"
                        href="#"
                        icon="film" 
                        active>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.ui_components')"
                        href="#"
                        icon="window" 
                        active>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.settings')"
                        href="#"
                        icon="cog" 
                        active>
                    </menu-item>
                    
                    <menu-item
                        :title="__('voyager::plugins.plugins')"
                        href="#"
                        icon="puzzle-piece" 
                        active>
                    </menu-item>
                    </nav>
                </div>
                <div class="flex-shrink-0 flex border-t sidebar-border p-4">
                    <button class="rounded-full bg-gray-300 dark:bg-gray-700 outline-none px-2 py-2 rounded inline-flex items-center" @click="$store.toggleDarkMode()">
                        <icon :icon="$store.darkmode ? 'sun' : 'moon'"></icon>
                    </button>
                    <img src="#" class="rounded-full m-4 w-8" alt="User Avatar">
                </div>
            </div>
            <div class="flex-shrink-0 w-14"></div>
        </div>
    </div>

    <!-- Desktop sidebar -->
    <div class="hidden md:flex md:flex-shrink-0 sidebar h-full" v-if="$store.sidebarOpen" :key="'desktop_sidebar'">
        <div class="flex flex-col w-64 border-r sidebar-border">
            <div class="h-0 flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <icon icon="helm" :size="10" class="icon"></icon>
                    <span class="font-black text-lg uppercase ltr:pl-2 rtl:pr-2 title">
                        {{ $settings.setting('admin.sidebar-title', 'Voyager II') }}
                    </span>
                </div>
                <nav class="mt-4 flex-1 px-2">
                    <menu-item
                        :title="__('voyager::generic.dashboard')"
                        to="/"
                        icon="dashboard"
                        exact>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.breads')"
                        to="/breads"
                        icon="bread"
                        :is-open="breadsItemOpen">
                        <div>
                            <menu-item
                                v-for="(bread, i) in $store.breads"
                                :key="i"
                                :title="translate(bread.name_plural, true)"
                                :to="'/bread/'+translate(bread.table, true)"
                                :icon="bread.icon">
                            </menu-item>
                        </div>
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.media')"
                        to="/media"
                        icon="film">
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.ui_components')"
                        to="/ui"
                        icon="window">
                    </menu-item>

                    <menu-item
                        :title="__('voyager::generic.settings')"
                        to="/settings"
                        icon="cog">
                    </menu-item>
                    
                    <menu-item
                        :title="__('voyager::plugins.plugins')"
                        to="/plugins"
                        icon="puzzle-piece">
                    </menu-item>

                    <hr class="my-3 sidebar-border" v-if="$store.breads.length > 0" />

                    <menu-item
                        v-for="(bread, i) in $store.breads"
                        :key="i"
                        :title="translate(bread.name_plural, true)"
                        :to="'/'+translate(bread.slug, true)"
                        :icon="bread.icon"
                        :badge="bread.badge"
                        badge-content="XYZ"
                        :badge-color="bread.color || 'accent'"
                        :badge-dot="false">
                    </menu-item>
                </nav>
            </div>
            <div class="flex-shrink-0 inline-flex border-t sidebar-border p-4 h-auto overflow-x-hidden">
                <button class="button accent small icon-only" @click="$store.toggleDarkMode()">
                    <icon :icon="$store.darkmode ? 'sun' : 'moon'" />
                </button>
                <button class="button accent small icon-only" v-scroll-to="''">
                    <icon icon="arrow-circle-up" />
                </button>
                <button class="button accent small icon-only" @click="$store.toggleDirection()">
                    <icon :icon="$store.rtl ? 'left-to-right-text-direction' : 'right-to-left-text-direction'" />
                </button>
            </div>
        </div>
    </div>
</div>
</template>
<script>
import router from '../../js/router';

export default {
    router,
    computed: {
        breadsItemOpen: function () {
            return this.$route.path.startsWith('/breads') || this.$route.path.startsWith('/bread/');
        }
    }
}
</script>