<template>
    <div>
        <div class="voyager-table striped mt-0">
            <table>
                <thead>
                    <tr>
                        <th class="hidden md:table-cell"></th>
                        <th class="hidden md:table-cell">{{ __('voyager::generic.type') }}</th>
                        <th>{{ __('voyager::generic.column') }}</th>
                        <th>{{ __('voyager::generic.title') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.link_to') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.searchable') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.orderable') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::builder.order_default') }}</th>
                        <th class="hidden md:table-cell">{{ __('voyager::generic.translatable') }}</th>
                        <th class="flex justify-end">{{ __('voyager::generic.actions') }}</th>
                    </tr>
                </thead>
                <Draggable
                    tag="tbody"
                    itemTag="tr"
                    v-model="formfields"
                    index="uuid"
                    handle=".dd-handle"
                >
                    <template #item="{ item: formfield, index }">
                        <td class="hidden md:table-cell dd-handle cursor-move" v-tooltip="__('voyager::generic.move')">
                            <div class="h-5 w-5">
                                <Icon icon="selector" />
                            </div>
                        </td>
                        <td class="hidden md:table-cell">{{ getFormfieldByType(formfield.type).name }}</td>
                        <td>
                            <select class="input small w-full" v-model="formfield.column">
                                <optgroup :label="__('voyager::builder.columns')" v-if="getFormfieldByType(formfield.type).allow_columns">
                                    <option v-for="(column, i) in columns" :key="'column_'+i" :value="{column: column, type: 'column'}">
                                        {{ column }}
                                    </option>
                                </optgroup>
                                <optgroup :label="__('voyager::builder.computed')" v-if="getFormfieldByType(formfield.type).allow_computed_props && computed.length > 0">
                                    <option v-for="(prop, i) in computed" :key="'computed_'+i" :value="{column: prop, type: 'computed'}">
                                        {{ prop }}
                                    </option>
                                </optgroup>
                                <template v-for="(relationship, i) in relationships" :key="'relationship_'+i">
                                    <optgroup :label="relationship.method" v-if="getFormfieldByType(formfield.type).allow_relationship_props">
                                        <option v-for="(column, i) in relationship.columns" :key="'column_'+i" :value="{column: relationship.method+'.'+column, type: 'relationship'}">
                                            {{ relationship.method+'.'+column }}
                                        </option>
                                    </optgroup>
                                </template>
                            </select>
                        </td>
                        <td>
                            <LanguageInput
                                class="input small w-full"
                                type="text" placeholder="Title"
                                v-model="formfield.title" />
                        </td>
                        <td class="inline-flex items-center space-x-1">
                            <select
                                class="input small w-full"
                                v-model="formfield.link_to"
                                :disabled="getRelatedBread(formfield.column) === null && formfield.column.type == 'relationship'"
                            >
                                <option :value="null">{{ __('voyager::generic.nothing') }}</option>
                                <option value="edit">{{ __('voyager::generic.edit') }}</option>
                                <option value="read">{{ __('voyager::generic.read') }}</option>
                            </select>
                            <Icon
                                icon="information-circle"
                                :size="6"
                                v-if="getRelatedBread(formfield.column) !== null"
                                v-tooltip="__('voyager::builder.links_to_bread')"
                            />
                            <Icon
                                icon="information-circle"
                                :size="6"
                                class="text-red-500"
                                v-if="getRelatedBread(formfield.column) === null && formfield.column.type == 'relationship'"
                                v-tooltip="__('voyager::builder.cannot_link')"
                            />
                        </td>
                        <td class="hidden md:table-cell">
                            <input
                                class="input"
                                type="checkbox"
                                v-model="formfield.searchable" />
                        </td>
                        <td class="hidden md:table-cell">
                            <input
                                class="input"
                                type="checkbox"
                                v-model="formfield.orderable"
                                :disabled="formfield.column.type !== 'column'" />
                        </td>
                        <td class="hidden md:table-cell">
                            <input
                                class="input"
                                type="radio"
                                :disabled="formfield.column.type !== 'column'"
                                :checked="options.default_order_column && options.default_order_column == formfield.column"
                                v-model="options.default_order_column"
                                v-bind:value="formfield.column" />
                        </td>
                        <td class="hidden md:table-cell">
                            <input
                                type="checkbox"
                                class="input"
                                v-model="formfield.translatable"
                                :disabled="!getFormfieldByType(formfield.type).can_be_translated">
                        </td>
                        <td class="flex flex-no-wrap space-x-1 justify-end">
                            <SlideIn :title="__('voyager::generic.options')">
                                <template #actions>
                                    <LocalePicker />
                                </template>
                                <component
                                    :is="getFormfieldByType(formfield.type).builder_component"
                                    v-model:options="formfield.options"
                                    :column="formfield.column"
                                    :columns="columns"
                                    action="list-options" />

                                <div class="input-group mt-2">
                                    <label class="label mt-4">{{ __('voyager::generic.component') }}</label>
                                    <input type="text" class="input w-full" v-model="formfield.component">
                                </div>
                                <div class="input-group mt-2">
                                    <label class="label">{{ __('voyager::generic.classes') }}</label>
                                    <input type="text" class="input w-full" v-model="formfield.options.classes">
                                </div>
                                <template #opener>
                                    <button class="button">
                                        <Icon icon="cog" />
                                        <span>{{ __('voyager::generic.options') }}</span>
                                    </button>
                                </template>
                            </SlideIn>
                            <button class="button" @click="$emit('delete', index)">
                                <Icon icon="trash" class="text-red-500" />
                                <span>{{ __('voyager::generic.delete') }}</span>
                            </button>
                        </td>
                    </template>
                </Draggable>
            </table>
        </div>

        <Collapsible :title="`${__('voyager::generic.filters')} (${options.filters.length || 0})`" closed ref="filters_collapsible">
            <template #actions>
                <button class="button small" @click.stop="addFilter">
                    <Icon icon="plus" class="text-green-500" />
                </button>
            </template>
            <div class="voyager-table">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('voyager::generic.name') }}</th>
                            <th>{{ __('voyager::generic.column') }}</th>
                            <th>{{ __('voyager::generic.operator') }}</th>
                            <th>{{ __('voyager::builder.value_or_scope') }}</th>
                            <th>{{ __('voyager::generic.color') }}</th>
                            <th>{{ __('voyager::generic.icon') }}</th>
                            <th class="flex justify-end">{{ __('voyager::generic.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(f, key) in options.filters" :key="'filter-'+key">
                            <td>
                                <LanguageInput
                                    class="input small w-full"
                                    type="text" :placeholder="__('voyager::generic.name')"
                                    v-model="f.name"
                                />
                            </td>
                            <td>
                                <select class="input small w-full" v-model="f.column">
                                    <option :value="null">{{ __('voyager::generic.none') }}</option>
                                    <option v-for="column in columns" :key="column">{{ column }}</option>
                                </select>
                            </td>
                            <td>
                                <select class="input small w-full" v-model="f.operator" :disabled="f.column === null">
                                    <option value="=">{{ __('voyager::builder.operators.equals') }}</option>
                                    <option value="!=">{{ __('voyager::builder.operators.not_equals') }}</option>
                                    <option value=">=">{{ __('voyager::builder.operators.bigger_than') }}</option>
                                    <option value=">">{{ __('voyager::builder.operators.bigger') }}</option>
                                    <option value="<=">{{ __('voyager::builder.operators.smaller_than') }}</option>
                                    <option value="<">{{ __('voyager::builder.operators.smaller') }}</option>
                                    <option value="like">{{ __('voyager::builder.operators.like') }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="input small w-full" v-model="f.value">
                            </td>
                            <td>
                                <ColorPicker v-model="f.color" :size="2" add-none />
                            </td>
                            <td>
                                <Modal :ref="`filter_icon_modal_${key}`" :title="__('voyager::generic.select_icon')">
                                    <IconPicker v-on:select="f.icon = $event; $refs[`filter_icon_modal_${key}`].close();" />
                                    <template #opener>
                                        <div class="w-full">
                                            <button class="button">
                                                <Icon class="my-1 content-center" :icon="f.icon ? f.icon : 'ban'" :key="key + (f.icon ? f.icon : 'ban')" />
                                            </button>
                                        </div>
                                    </template>
                                    <template #actions>
                                        <button class="button" @click="f.icon = null; $refs['filter_icon_modal_'+key].close()">
                                            {{ __('voyager::generic.none') }}
                                        </button>
                                    </template>
                                </Modal>
                            </td>
                            <td class="flex justify-end">
                                <button class="button small" @click.stop="removeFilter(key)">
                                    <Icon icon="trash" class="text-red-500" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Collapsible>
    </div>
</template>

<script>
import Draggable from '@components/UI/Draggable.vue';

export default {
    components: { Draggable },
    emits: ['update:formfields', 'update:options', 'delete'],
    props: {
        computed: Array,
        columns: Array,
        relationships: Array,
        formfields: Array,
        options: Object,
    },
    data() {
        return {
            colors: this.colors,
        };
    },
    methods: {
        addFilter() {
            this.$refs.filters_collapsible.open();
            var options = this.options;
            if (!this.isArray(options.filters)) {
                options.filters = [];
            }
            options.filters.push({
                name: '',
                column: null,
                operator: '=',
                value: '',
                color: 'accent',
                icon: null,
            });
            this.$emit('update:options', options);
        },
        removeFilter(key) {
            var options = this.options;
            options.filters.splice(key, 1);
            this.$emit('update:options', options);
        },
        getRelatedBread(column) {
            if (column.type == 'relationship') {
                let method = column.column.split('.')[0];
                let relationship = this.relationships.where('method', method).first();
                if (relationship && relationship.hasOwnProperty('bread') && relationship.bread) {
                    return relationship.bread;
                }
            }

            return null;
        }
    },
};
</script>