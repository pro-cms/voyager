<template>
    <Card :title="__('voyager::generic.breads')" icon="bread">
        <template #actions>
            <div class="flex flex-wrap items-center space-x-1">
                <button class="button space-x-1" @click="reload" :disabled="store.pageLoading">
                    <Icon icon="refresh" class="animate-spin-reverse" :size="store.pageLoading ? 4 : 0" :transition-size="4" />
                    <span>{{ __('voyager::generic.reload') }}</span>
                </button>
                <LocalePicker />
            </div>
        </template>
        <div class="voyager-table striped" :class="[store.pageLoading ? 'store.pageLoading' : '']">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('voyager::generic.table') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::generic.slug') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.name_singular') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.name_plural') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.model') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.lists') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.views') }}</th>
                        <th class="flex justify-end">{{ __('voyager::generic.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="table in tables" v-bind:key="table">
                        <td>{{ table }}</td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ translate(getBread(table).slug) }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ translate(getBread(table).name_singular) }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ translate(getBread(table).name_plural) }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ getBread(table).model }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ getBread(table).layouts.where('type', 'list').length }}</span>
                        </td>
                        <td class="hidden md:table-cell">
                            <span v-if="hasBread(table)">{{ getBread(table).layouts.where('type', 'view').length }}</span>
                        </td>
                        <td class="flex flex-no-wrap justify-end space-x-1">
                            <template v-if="hasBread(table)">
                                <Link class="button" :href="route('voyager.'+translate(getBread(table).slug, true)+'.browse')">
                                    <Icon icon="globe" :size="4" class="text-accent-500" />
                                    <span>{{ __('voyager::generic.browse') }}</span>
                                </Link>
                                <button class="button" @click="backupBread(table)">
                                    <Icon icon="clock" :class="[backingUp ? 'animate-spin-reverse' : '']" :size="4" class="text-green-500" />
                                    <span>{{ __('voyager::generic.backup') }}</span>
                                </button>
                                <Dropdown v-if="getBackupsForTable(table).length > 0" placement="bottom">
                                    <div>
                                        <a v-for="(bu, i) in getBackupsForTable(table)"
                                            :key="'rollback-'+i"
                                            href="#"
                                            @click.prevent="rollbackBread(table, bu)"
                                            class="link">
                                            {{ bu.date }}
                                        </a>
                                    </div>
                                    <template #opener>
                                        <button class="button">
                                            <Icon icon="clock" :size="4" class="text-green-500" />
                                            <span>{{ __('voyager::builder.rollback') }} ({{ getBackupsForTable(table).length }})</span>
                                        </button>
                                    </template>
                                </Dropdown>
                                <Link as="button" class="button" :href="route('voyager.bread.edit', table)">
                                    <Icon icon="pencil" :size="4" class="text-yellow-500" />
                                    <span>{{ __('voyager::generic.edit') }}</span>
                                </Link>
                                <button class="button" @click="deleteBread(table)">
                                    <Icon :icon="deleting ? 'refresh' : 'trash'" class="text-red-500" :class="[deleting ? 'animate-spin-reverse' : '']" :size="4" />
                                    <span>{{ __('voyager::generic.delete') }}</span>
                                </button>
                            </template>
                            <Link as="button" v-else class="button" :href="route('voyager.bread.create', table)">
                                <Icon icon="plus" :size="4" class="text-green-500" />
                                <span class="hidden md:block">
                                    {{ __('voyager::generic.add_type', { type: __('voyager::generic.bread') }) }}
                                </span>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/inertia-vue3';

import Store from '@/store';

export default {
    props: {
        tables: {
            type: Array,
            default: () => [],
        },
        breads: {
            type: Array,
            default: () => [],
        },
        backups: {
            type: Array,
            default: () => [],
        }
    },
    components: {
        Link
    },
    data() {
        return {
            backingUp: false,
            deleting: false,
            store: Store,
        };
    },
    methods: {
        hasBread(table) {
            return this.getBread(table) !== null;
        },
        getBread(table) {
            var bread = null;
            this.breads.forEach(b => {
                if (b.table == table) {
                    bread = b;
                }
            });

            return bread;
        },
        deleteBread(table) {
            new this
            .$notification(this.__('voyager::builder.delete_bread_confirm', {bread: table}))
            .color('yellow')
            .timeout()
            .confirm()
            .show()
            .then((result) => {
                if (result) {
                    this.deleting = true;
                    axios.delete(this.route('voyager.bread.delete', table))
                    .then(() => {
                        new this.$notification(this.__('voyager::builder.delete_bread_success', {bread: table})).color('green').timeout().show();
                    })
                    .catch((response) => {})
                    .then(() => {
                        this.reload();
                        this.deleting = false;
                    });
                }
            });
        },
        backupBread(table) {
            this.backingUp = true;

            axios.post(this.route('voyager.bread.backup-bread'), {
                table: table
            })
            .then((response) => {
                new this.$notification(this.__('voyager::builder.bread_backed_up', { name: response.data })).timeout().show();
            })
            .catch((response) => {})
            .then(() => {
                this.backingUp = false;
                this.reload();
            });
        },
        rollbackBread(table, backup) {
            axios.post(this.route('voyager.bread.rollback-bread'), {
                table: table,
                path: backup.path
            })
            .then(() => {
                new this.$notification(this.__('voyager::builder.bread_rolled_back', { date: backup.date })).timeout().show();
            })
            .catch((response) => {})
            .then(() => {
                this.reload();
            });
        },
        getBackupsForTable(table) {
            return this.backups.where('table', table);
        },
        reload() {
            this.$inertia.get(route('voyager.bread.index'));
        }
    },
};
</script>