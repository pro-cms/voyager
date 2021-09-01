<template>
    <div>
        <draggable class="flex flex-wrap w-full min-h-64" :modelValue="formfields" @update:modelValue="$emit('update:formfields', JSON.parse(JSON.stringify($event)))" item-key="" group="view-builder-dd" handle=".dd-handle">
            <template #item="{ element: formfield, index: key }">
                <div
                    v-show="formfield.tab === tab"
                    class="m-0" :class="formfield.options.width"
                    uses="w-1/6 w-2/6 w-3/6 w-4/6 w-5/6 w-full"
                >
                    <Card :title="translate(formfield.options.title) || ''" :title-size="5">
                        <template #actions>
                            <div class="flex space-x-1">
                                <button class="button small dd-handle cursor-move" v-tooltip="__('voyager::generic.move')">
                                    <Icon icon="arrows-expand" />
                                </button>
                                <button class="button small" @mousedown="startResize(key)" v-tooltip="__('voyager::builder.resize')">
                                    <Icon icon="switch-horizontal" class="cursor-move" />
                                </button>
                                <Dropdown>
                                    <div>
                                        <div class="link" @click="$emit('update:formfield_tab', { key, tab: null })">
                                            {{ __('voyager::bread.no_tab') }}
                                        </div>
                                        <div class="link" v-for="tab in tabs" :key="`tab-${tab}`" @click="$emit('update:formfield_tab', { key, tab })">
                                            {{ translate(tab) }}
                                        </div>
                                    </div>
                                    <template #opener>
                                        <button class="button small">
                                            <Icon icon="collection" />
                                        </button>
                                    </template>
                                </Dropdown>
                                <SlideIn :title="__('voyager::generic.options')">
                                    <template #actions>
                                        <LocalePicker />
                                    </template>
                                    <div class="input-group mt-2" v-if="!fromRepeater">
                                        <label class="label mt-4">{{ __('voyager::generic.column') }}</label>
                                        <select
                                            class="input w-full"
                                            v-model="formfield.column"
                                            v-show="getFormfieldByType(formfield.type).allow_columns || getFormfieldByType(formfield.type).allow_computed_props || getFormfieldByType(formfield.type).allow_relationship_props || getFormfieldByType(formfield.type).allow_relationships"
                                        >
                                            <optgroup :label="__('voyager::builder.columns')" v-if="getFormfieldByType(formfield.type).allow_columns">
                                                <option v-for="(column, i) in columns" :key="'column_'+i" :value="{column: column, type: 'column'}">
                                                    {{ column }}
                                                </option>
                                            </optgroup>
                                            <optgroup :label="__('voyager::builder.computed')" v-if="getFormfieldByType(formfield.type).allow_computed_props">
                                                <option v-for="(prop, i) in computed" :key="'computed_'+i" :value="{column: prop, type: 'computed'}">
                                                    {{ prop }}
                                                </option>
                                            </optgroup>
                                            <template v-for="(relationship, i) in relationships" :key="'relationship_'+i">
                                            <optgroup :label="relationship.method" v-if="getFormfieldByType(formfield.type).allow_relationship_props">
                                                <option v-for="(column, i) in relationship.columns" :key="'column_'+i" :value="{column: relationship.method+'.'+column, type: 'relationship'}">
                                                    {{ column }}
                                                </option>
                                                <template v-for="(column, i) in relationship.pivot" :key="'pivot_'+i">
                                                    <option :value="{column: relationship.method+'.pivot.'+column, type: 'relationship'}" v-if="getFormfieldByType(formfield.type).allow_relationship_pivots">
                                                        pivot.{{ column }}
                                                    </option>
                                                </template>
                                            </optgroup>
                                            </template>
                                            <optgroup v-if="getFormfieldByType(formfield.type).allow_relationships" :label="__('voyager::generic.relationships')">
                                                <option v-for="(relationship, i) in relationships" :key="'relationship_'+i" :value="{column: relationship.method, type: 'relationship'}">
                                                    {{ relationship.method }}
                                                </option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="input-group mt-2" v-else-if="fromRepeater">
                                        <label class="label">{{ __('voyager::generic.key') }}</label>
                                        <input
                                            class="input w-full"
                                            type="text" :placeholder="__('voyager::generic.key')"
                                            v-model="formfield.column.column" />
                                    </div>
                                    <div v-if="getFormfieldByType(formfield.type).can_be_translated" class="input-group mt-2">
                                        <label class="label mt-4">{{ __('voyager::generic.translatable') }}</label>
                                        <input type="checkbox" class="input" v-model="formfield.translatable">
                                    </div>
                                    <div class="input-group mt-2">
                                        <label class="label mt-4">{{ __('voyager::generic.title') }}</label>
                                        <LanguageInput
                                            class="input w-full"
                                            type="text" :placeholder="__('voyager::generic.title')"
                                            v-model="formfield.options.title" />
                                    </div>
                                    <div class="input-group mt-2">
                                        <label class="label mt-4">{{ __('voyager::generic.description') }}</label>
                                        <LanguageInput
                                            class="input w-full"
                                            type="text" :placeholder="__('voyager::generic.description')"
                                            v-model="formfield.options.description" />
                                    </div>

                                    <component
                                        :is="getFormfieldByType(formfield.type).builder_component"
                                        v-model:orgoptions="formfield.options"
                                        :column="formfield.column"
                                        :columns="columns"
                                        :relationships="relationships"
                                        action="view-options" />


                                    <div class="input-group mt-2">
                                        <label class="label mt-4">{{ __('voyager::generic.component') }}</label>
                                        <input type="text" class="input w-full" v-model="formfield.component">
                                    </div>

                                    <div class="input-group mt-2">
                                        <label class="label mt-4">{{ __('voyager::generic.classes') }}</label>
                                        <input type="text" class="input w-full" v-model="formfield.options.classes">
                                    </div>

                                    <BreadBuilderValidation v-model="formfield.validation" />

                                    <template #opener>
                                        <button class="button small">
                                            <Icon icon="cog" />
                                        </button>
                                    </template>
                                </SlideIn>
                                <button class="button small" @click="$emit('delete', key)">
                                    <Icon icon="trash" class="text-red-500" />
                                </button>
                            </div>
                        </template>
                        <component
                            :is="getFormfieldByType(formfield.type).builder_component"
                            v-bind:orgoptions="formfield.options"
                            :class="formfield.options.classes"
                            :column="formfield.column"
                            :columns="columns"
                            action="view" />
                        <p class="description" v-if="translate(formfield.options.description) !== ''">
                            {{ translate(formfield.options.description) }}
                        </p>
                    </Card>
                </div>
            </template>
        </draggable>
    </div>
</template>

<script>
import draggable from 'vuedraggable';

import BreadBuilderValidation from '@components/Builder/ValidationForm.vue';

export default {
    components: {
        BreadBuilderValidation,
        draggable,
    },
    emits: ['delete', 'update:formfields', 'update:options', 'update:formfield_tab'],
    props: {
        computed: Array,
        columns: Array,
        relationships: Array,
        formfields: Array,
        options: Object,
        tab: {
            type: [Number, null],
            default: null,
        },
        tabs: {
            type: Array,
            default: () => []
        },
        fromRepeater: {
            type: Boolean,
            default: false,
        }
    },
    data() {
        return {
            resizingFormfield: null,
            sizes: [
                'w-1/6',
                'w-2/6',
                'w-3/6',
                'w-4/6',
                'w-5/6',
                'w-full',
            ]
        };
    },
    methods: {
        startResize(key) {
            this.resizingFormfield = key;
        },
        prev(formfield) {
            this.$emit('update:formfields', this.formfields.moveElementUp(formfield));
        },
        next(formfield) {
            this.$emit('update:formfields', this.formfields.moveElementDown(formfield));
        },
    },
    mounted() {
        window.addEventListener('mouseup', () => {
            this.resizingFormfield = null;
        });

        this.$el.addEventListener('mousemove', (e) => {
            if (this.resizingFormfield !== null) {
                e.preventDefault();
                var rect = this.$el.getBoundingClientRect();
                var x = e.clientX - rect.left - 50;
                var threshold = rect.width / (this.sizes.length - 1);
                var size = Math.min(Math.max(Math.ceil(x / threshold), 0), this.sizes.length);
                this.formfields[this.resizingFormfield].options.width = this.sizes[size];
            }
        });
    }
};
</script>