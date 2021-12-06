<template>
    <nav class="flex items-center mb-3 sm:px-3 md:px-4">
        <button @click.stop="toggleSidebar()" class="button small mx-2 flex-none" aria-label="Toggle navbar">
            <Icon icon="dots-vertical" :class="store.sidebarOpen ? 'rotate-0' : 'rotate-90'" />
        </button>
        <Search
            class="h-full flex-grow flex"
            :placeholder="store.searchPlaceholder"
            :mobile-placeholder="__('voyager::generic.search')"
        />
        <div class="mt-1 text-right z-30">
            <Dropdown placement="bottom-end">
                <div>
                    <div class="flex space-x-4 items-center px-6 py-4">
                        <img class="h-10 w-10 rounded-full flex-no-shrink" :src="store.user.avatar" alt="">
                        <div>
                            <p class="font-semibold leading-none">{{ store.user.name }}</p>
                            <p>
                                <Link href="#" class="text-sm leading-none hover:underline">
                                    {{ __('voyager::generic.view_profile') }}
                                </Link>
                            </p>
                        </div>
                    </div>
                    <template v-for="(item, i) in store.user.items" :key="`user-item-${i}`">
                        <template v-if="item.divider">
                            <hr class="sidebar-border" />
                        </template>
                        <Link v-else :href="item.href" class="link">
                            {{ item.title }}
                        </Link>
                    </template>
                </div>
                <template #opener>
                    <button class="inline-flex justify-end w-48 max-w-sm space-x-2 items-center font-semibold focus:outline-none">
                        <img class="h-6 w-6 rounded-full flex-no-shrink" :src="store.user.avatar" alt="" v-if="store.user.avatar">
                        <span class="hidden md:block whitespace-no-wrap">
                            {{ __('voyager::generic.hello_user', { user: store.user.name }) }}
                        </span>
                        <Icon icon="chevron-down" :size="4" />
                    </button>
                </template>
            </Dropdown>
        </div>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

import Store from '@/store';
import Search from '@components/Layout/Search.vue';

export default {
    components: {
        Search,
        Link
    },
    data() {
        return {
            store: Store
        }
    }
}
</script>