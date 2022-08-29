<template>
    <div class="w-full" @keydown.esc="search('')">
        <Modal ref="results_modal" :title="placeholder" icon="magnifying-glass" @closed="query = ''">
            <div>
                <input
                    autocomplete="off"
                    type="text"
                    class="input w-full mb-2"
                    @dblclick="query = ''"
                    @keydown.esc="query = ''"
                    v-model="query" @input="search" :placeholder="placeholder"
                    ref="search_input"
                >
                <div v-if="query === null || query === ''">
                    <h5>{{ __('voyager::generic.enter_query') }}</h5>
                </div>
                <div class="grid gap-2" :class="gridClasses" uses="grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4" v-else>
                    <Card v-for="(bread, table) in searchResults" :key="'bread-results-'+table" class="w-full" :title="translate(getBreadByTable(table).name_plural, true)" :titleSize="5">
                        <template v-if="bread.hasOwnProperty('results') && bread.hasOwnProperty('count') && bread.count > 0">
                            <Card v-for="(result, key) in bread.results" :key="'result-'+table+'-'+key" class="w-full text-lg truncate" no-header>
                                <Link :href="getResultUrl(table, key)">
                                    {{ translate(result, true) }}
                                </Link>
                            </Card>
                            <Link :href="moreUrl(table)" v-if="bread.count > Object.keys(bread.results).length" class="italic rounded-md text-lg">
                                {{ __('voyager::generic.more_results', { num: (bread.count - Object.keys(bread.results).length)}) }}
                            </Link>
                        </template>
                        <template v-else-if="bread.loading">
                            {{ __('voyager::generic.loading_please_wait') }}
                        </template>
                        <template v-else>
                            {{ __('voyager::generic.no_results') }}
                        </template>
                    </Card>
                </div>
            </div>
        </Modal>
        <input
            autocomplete="off"
            type="text"
            class="py-2 hidden sm:block text-lg appearance-none bg-transparent leading-normal w-full focus:outline-none"
            @dblclick="query = ''"
            @keydown.esc="query = ''"
            v-model="query" @input="search" :placeholder="placeholder"
        >
        <input
            autocomplete="off"
            type="text"
            class="py-2 block sm:hidden text-lg appearance-none bg-transparent leading-normal w-full focus:outline-none"
            v-model="query" @input="search" :placeholder="mobilePlaceholder"
        >
    </div>
</template>
<script>
import { nextTick } from 'vue';
import axios from 'axios';
import debounce from 'debounce';
import { Link } from '@inertiajs/inertia-vue3';

import Store from '@/store';

export default {
    components: { Link },
    props: ['placeholder', 'mobilePlaceholder'],
    data() {
        return {
            searchResults: {},
            query: '',
            loading: false,
        };
    },
    computed: {
        gridClasses() {
            return [
                'grid-cols-' + this.max(1),
                'md:grid-cols-' + this.max(2),
                'lg:grid-cols-' + this.max(3),
                'xl:grid-cols-' + this.max(4)
            ];
        }
    },
    methods: {
        max(val) {
            if (Object.keys(this.searchResults).length < val) {
                return Object.keys(this.searchResults).length;
            }

            return val;
        },
        search: debounce(function (e) {
            this.searchResults = {};
            if (this.query == '') {
                return;
            }

            this.loading = true;
            Store.pageLoading = true;
            axios.post(this.route('voyager.globalsearch'), {
                query: this.query,
            })
            .then((response) => {
                this.searchResults = response.data;
            })
            .catch((response) => {})
            .then(() => {
                this.loading = false;
                Store.pageLoading = false;
            });
        }, 250),
        moreUrl(table) {
            var bread = this.getBreadByTable(table);

            return this.route('voyager.'+this.translate(bread.slug, true)+'.browse')+'?global='+this.query;
        },
        getResultUrl(table, key) {
            var bread = this.getBreadByTable(table);

            return this.route('voyager.'+this.translate(bread.slug, true)+'.read', key);
        },
        getBreadByTable(table) {
            if (this.isObject(Store.breads)) {
                return Object.values(Store.breads).where('table', table).first();
            }

            return Store.breads.where('table', table).first();
        },
        openModal() {
            this.$refs.results_modal.open();
            nextTick(() => {
                this.$refs.search_input.focus();
            });
        }
    },
    created() {
        this.$watch(() => this.query, (query) => {
            if (query !== '') {
                this.loading = true;
                this.openModal();
                this.search(query);
            }
        });
    },
    mounted() {
        document.addEventListener('keydown', (e) => {
            if (typeof e === 'object' && e instanceof KeyboardEvent) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    this.openModal();
                }
            }
        });
    }
};
</script>