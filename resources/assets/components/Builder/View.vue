<template>
    <div>
        <Draggable
            class="flex flex-wrap w-full min-h-64"
            :modelValue="formfields"
            @update:model-value="this.$emit('update:formfields', $event)"
            index="uuid"
            handle=".dd-handle"
            :itemAttrs="formfieldAttributes"
        >
            <template #item="{ item: formfield, index }" class="m-0" :class="formfield.options.width">
                <div uses="w-1/6 w-2/6 w-3/6 w-4/6 w-5/6 w-full">
                    <Card :title="translate(formfield.options.title) || ''" :title-size="5">
                        <template #actions>
                            <div class="flex space-x-1">
                                <button class="button small dd-handle cursor-move" v-tooltip="__('voyager::generic.move')">
                                    <Icon icon="arrows-pointing-out" />
                                </button>
                                <button class="button small" @mousedown="this.$emit('smaller', formfield.uuid)" v-tooltip="__('voyager::builder.smaller')" :disabled="formfield.options.width == sizes[0]">
                                    <Icon icon="minus" />
                                </button>
                                <button class="button small" @mousedown="this.$emit('bigger', formfield.uuid)" v-tooltip="__('voyager::builder.bigger')" :disabled="formfield.options.width == sizes[sizes.length - 1]">
                                    <Icon icon="plus" />
                                </button>
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
                                        v-model:options="formfield.options"
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
                                <button class="button small" @click="$emit('delete', index)">
                                    <Icon icon="trash" class="text-red-500" />
                                </button>
                            </div>
                        </template>
                        <component
                            :is="getFormfieldByType(formfield.type).builder_component"
                            v-model:options="formfield.options"
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
        </Draggable>
    </div>
</template>

<script>
import Draggable from '@components/UI/Draggable.vue';

import BreadBuilderValidation from '@components/Builder/ValidationForm.vue';

export default {
    components: {
        BreadBuilderValidation,
        Draggable,
    },
    emits: ['delete', 'update:formfields', 'update:options', 'smaller', 'bigger'],
    props: {
        computed: Array,
        columns: Array,
        relationships: Array,
        formfields: Array,
        options: Object,
        sizes: Array,
        fromRepeater: {
            type: Boolean,
            default: false,
        }
    },
    methods: {
        formfieldAttributes(formfield) {
            return {
                class: formfield.options.width
            };
        },
    }
};
</script>