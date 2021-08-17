<template>
    <div :class="!isLogin ? 'h-screen flex overflow-hidden' : null ">
        <div>
            <FadeTransition>
                <div class="absolute w-full h-1.5 overflow-hidden" style="z-index: 9999;" v-if="store.pageLoading">
                    <div class="indeterminate">
                        <div class="before rounded" :class="`bg-blue-500`"></div>
                        <div class="after rounded" :class="`bg-blue-500`"></div>
                    </div>
                </div>
            </FadeTransition>
        </div>
        <template v-if="!isLogin">
            <Sidebar />
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                <main class="flex-1 relative z-0 overflow-y-auto pt-2 pb-6 outline-none" id="content">
                    <div id="top"></div>
                    <Navbar />
                    <div class="mx-auto sm:px-3 md:px-4">
                        <slot></slot>
                    </div>
                </main>
            </div>
            
        </template>
        <template v-else>
            <slot />
        </template>
        <Notifications :position="store.notificationPosition" />
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

import Sidebar from '@components/Sidebar.vue';
import Navbar from '@components/Navbar.vue';
import Notifications from '@components/UI/Notifications.vue';
import EventBus from '@/eventbus';
import Store from '@/store';

export default {
    components: {
        Sidebar,
        Navbar,
        Notifications
    },
    data() {
        return {
            store: Store,
        };
    },
    created() {
        Inertia.on('navigate', (event) => {
            document.title = event.detail.page.props.page_title + ' - ' + Store.titleSuffix;
            Store.pageLoading = false;
        });

        Inertia.on('start', (event) => {
            Store.pageLoading = true;
        });

        Inertia.on('finish', (e) => {
            Store.pageLoading = false;
        });

        document.addEventListener('DOMContentLoaded', () => {
            Store.pageLoading = false;
        });

        // Toggle sidebar when cookie is set
        var sidebar_open = this.getCookie('sidebar-open');
        if (sidebar_open == 'false') {
            this.closeSidebar();
        }
        EventBus.on('sidebar-open', (open) => {
            this.setCookie('sidebar-open', open);
        });

        // Show dev server warning if not available
        if (Store.devServer.wanted && !Store.devServer.available) {
            new this.$notification(this.__('voyager::generic.dev_server_unavailable', { url: 'http://localhost:8081' }))
                    .color('yellow')
                    .timeout(5000)
                    .confirm()
                    .addButton({ key: true, value: this.__('voyager::generic.disable'), color: 'accent'})
                    .show()
                    .then((result) => {
                        if (result === true) {
                            axios.post(this.route('voyager.disable-dev-server'))
                            .then(() => {
                                new this.$notification(this.__('voyager::generic.dev_server_disabled')).show();
                            })
                            .catch(response => {})
                            .then(() => {});
                        }
                    });
        }

        axios.interceptors.request.use((config) => {
            Store.pageLoading = true;
            return config;
        }, (error) => {
            return Promise.reject(error);
        });

        axios.interceptors.response.use((response) => {
            Store.pageLoading = false;
            return response;
        }, (error) => {
            Store.pageLoading = false;
            let response = error;
            if (response.response.status !== 422) {
                if (response.hasOwnProperty('response')) {
                    response = response.response;
                }
                if (response.hasOwnProperty('data')) {
                    response = response.data;
                }

                var notification = new this.$notification(response.message).color('red').timeout();
                if (response.hasOwnProperty('stack')) {
                    notification = notification.message(response.stack);
                    notification = notification.title(response.message);
                }
        
                notification.show();
            }

            throw error;
        });
    },
    computed: {
        isLogin() {
            return usePage().component.value == 'Login';
        }
    },
}
</script>