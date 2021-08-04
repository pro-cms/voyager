<template>
    <slot v-if="action == 'query'"></slot>
    <template v-else-if="action == 'edit' || action == 'add'">
        <alert v-if="relationship === undefined" color="red" class="mt-2">
            {{ __('voyager::formfields.relationship.not_resolved') }}
        </alert>
        <template v-else-if="relationship.bread === null || options.list === null">
            <div class="flex-wrap space-x-1 space-y-1">
                <Badge v-for="select in selected" :key="`badge-${select.key}`" @clickIcon="handleInput(select, false)" icon="x">
                    {{ select.value }}
                </Badge>
                
            </div>
            <div class="voyager-table" :class="[loading ? 'loading' : '']">
                <table>
                    <thead>
                        <tr>
                            <th class="w-2"></th>
                            <th class="flex">
                                <div class="flex-none self-center">
                                    {{ translate(options.display_name, true) }}
                                </div>
                                <div class="flex flex-grow justify-end">
                                    <button class="button" v-if="options.allow_none" @click="removeAll">
                                        {{ __('voyager::generic.none') }}
                                    </button>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th class="w-2"></th>
                            <th>
                                <input class="input w-full" v-model="query" :placeholder="__('voyager::bread.search_type', {type: translate(options.display_name, true)})">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="result in results" :key="result.key">
                            <td class="w-2">
                                <input
                                    :type="relationship.multiple ? 'checkbox' : 'radio'"
                                    class="input"
                                    :name="`select-${column.column}`"
                                    :checked="(relationship.multiple ? (this.modelValue.includes(result.key)) : (this.modelValue === result.key))"
                                    @change="handleInput(result, $event.target.checked)"
                                />
                            </td>
                            <td>{{ result.value }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full flex">
                <div class="flex-none">
                    <Pagination
                        :page-count="pages"
                        @update:model-value="page = $event"
                        :model-value="page"
                    />
                </div>
                <div class="flex flex-grow justify-end">
                    <select class="input small" v-model.number="perPage">
                        <option>5</option>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                </div>
            </div>
        </template>
    </template>
    <template v-else-if="action == 'read'">
        <template v-if="relationship.bread === null || options.list === null">
            <div class="flex-wrap space-x-1 space-y-1">
                <Badge v-for="select in selected" :key="`badge-${select.key}`">
                    {{ select.value }}
                </Badge>
            </div>
        </template>
    </template>
</template>

<script>
import formfield from '@mixins/formfield';
import axios from 'axios';

export default {
    mixins: [formfield],
    data() {
        return {
            selected: [],
            results: [],
            pages: 0,
            page: 1,
            perPage: 5,
            arrayModel: [],
            singleModel: null,
            loading: false,
            query: '',
        }
    },
    computed: {
        relationship() {
            if (this.column.hasOwnProperty('column') && this.column.hasOwnProperty('type') && this.column.type == 'relationship') {
                return this.relationships.where('method', this.column.column).first();
            }

            return undefined;
        },
    },
    methods: {
        fetchRelationshipData() {
            // We only need to fetch data when the formfields should not represent a BREAD list
            if (this.relationship.bread === null || this.options.list === null) {
                this.loading = true;
                axios.post(this.route('voyager.'+this.translate(this.bread.slug, true)+'.relationship'), {
                    key: this.primaryKey,
                    method: this.column.column,
                    column: this.options.display_column,
                    page: this.page,
                    perPage: this.perPage,
                    query: this.query,
                })
                .then((response) => {
                    this.results = response.data.data;
                    this.selected = response.data.selected;
                    this.pages = response.data.pages;

                    if (this.relationship.multiple) {
                        this.$emit('update:modelValue', this.selected.pluck('key'));
                    } else {
                        this.$emit('update:modelValue', this.selected.first().key || null);
                    }
                })
                .catch((response) => {})
                .then(() => {
                    this.loading = false;
                });
            }
        },
        handleInput(result, checked) {
            if (checked) {
                if (this.relationship.multiple) {
                    this.$emit('update:modelValue', [...this.modelValue, result.key]);
                    this.selected.push(result);
                } else {
                    this.$emit('update:modelValue', result.key);
                    this.selected = [result];
                }
            } else {
                if (this.relationship.multiple) {
                    this.$emit('update:modelValue', [...this.modelValue.filter(x => x != result.key)]);
                    this.selected = this.selected.filter(x => x.key != result.key);
                } else {
                    this.$emit('update:modelValue', null);
                    this.selected = [];
                }
            }
        },
        removeAll() {
            if (this.relationship.multiple) {
                this.$emit('update:modelValue', []);
                this.selected = [];
            } else {
                this.$emit('update:modelValue', null);
                this.selected = [];
            }
        }
    },
    mounted() {
        this.fetchRelationshipData();

        this.$watch(() => this.page, () => {
            this.fetchRelationshipData();
        });

        this.$watch(() => this.perPage, () => {
            this.page = 1;
            this.fetchRelationshipData();
        });

        this.$watch(() => this.query, () => {
            this.fetchRelationshipData();
        });
    }
}
</script>