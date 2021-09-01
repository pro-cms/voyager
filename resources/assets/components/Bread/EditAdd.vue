<template>
    <div>
        <Card
            :noHeader="fromRepeater"
            :title="__('voyager::generic.'+currentAction+'_type', { type: translate(bread.name_singular, true) })"
            :class="fromRepeater ? 'border-none' : null"
            :style="fromRepeater ? 'box-shadow: none !important' : null"
            :icon="bread.icon"
        >
            <template #actions v-if="true">
                <div class="flex items-center space-x-2">
                    <Link class="button small" v-if="prevUrl !== ''" :href="prevUrl">
                        <Icon icon="chevron-left" />
                        <span>{{ __('voyager::generic.back') }}</span>
                    </Link>
                    <LocalePicker />
                </div>
            </template>
            <div>
                <div class="tabs mb-4" v-if="!fromRepeater && layout.tabs.length > 0">
                    <nav>
                        <a
                            v-if="layout.formfields.filter(x => x.tab === null).length > 0"
                            href="#"
                            class="tab"
                            :class="{ 'active': currentTab === null }"
                            @click.prevent="currentTab = null"
                        >
                            {{ __('voyager::bread.no_tab') }}
                        </a>

                        <a
                            v-for="(tab, i) in layout.tabs"
                            href="#"
                            class="tab"
                            :class="{ 'active': currentTab === i }"
                            @click.prevent="currentTab = i"
                        >
                        {{ translate(tab, true) }}
                        </a>
                    </nav>
                </div>
                <div class="flex flex-wrap w-full">
                    <template v-for="(formfield, key) in layout.formfields" :key="'formfield-'+key">
                        <div
                            v-if="formfield.tab === currentTab"
                            class="m-0 w-full"
                            :class="'md:' + formfield.options.width"
                            uses="md:w-1/6 md:w-2/6 md:w-3/6 md:w-4/6 md:w-5/6 md:w-full"
                        >
                            <component
                                :is="`Card`"
                                :title="translate(formfield.options.title, true)"
                                :title-size="5"
                                :show-title="translate(formfield.options.label, true) !== ''"
                            >
                                <CollapseTransition>
                                    <Alert v-if="getErrors(formfield.column).length > 0" color="red" class="mb-2">
                                        <span v-if="getErrors(formfield.column).length == 1">
                                            {{ getErrors(formfield.column)[0] }}
                                        </span>
                                        <ul class="list-disc" v-else>
                                            <li v-for="(error, i) in getErrors(formfield.column)" :key="'error-'+i">
                                                {{ error }}
                                            </li>
                                        </ul>
                                    </Alert>
                                </CollapseTransition>
                                <component
                                    :is="getComponentForType(formfield)"
                                    :modelValue="getData(formfield)"
                                    @update:modelValue="setData(formfield, $event)"
                                    :errors="getErrors(formfield.column)"
                                    :options="formfield.options"
                                    :column="formfield.column"
                                    :translatable="formfield.translatable"
                                    :from-repeater="fromRepeater"
                                    :action="currentAction"
                                    :primary-key="primaryKey"
                                    :class="formfield.options.classes"
                                />
                                <p class="description" v-if="translate(formfield.options.description, true) !== ''">
                                    {{ translate(formfield.options.description, true) }}
                                </p>
                            </component>
                        </div>
                    </template>
                </div>
                <button class="button green space-x-0" @click="save" :disabled="isSaving" v-if="!fromRepeater">
                    <Icon icon="refresh" class="animate-spin-reverse" :size="isSaving ? 4 : 0" :transition-size="4" />
                    <span>{{ __('voyager::generic.save') }}</span>
                </button>
            </div>
        </Card>
        <Collapsible v-if="!fromRepeater && jsonOutput" :title="__('voyager::generic.json_output')" closed>
            <JsonEditor v-model="output" />
        </Collapsible>
    </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/inertia-vue3';

import EventBus from '@/eventbus';
import Store from '@/store';

export default {
    components: { Link },
    emits: ['saved', 'output'],
    props: {
        bread: Object,
        action: String,
        input: Object,
        layout: Object,
        prevUrl: String,
        relationships: Array,
        primaryKey: [String, Number, Object],
        fromRepeater: {
            type: Boolean,
            default: false,
        }
    },
    provide() {
        return {
            bread: this.bread,
            relationships: this.relationships,
        }
    },
    data() {
        return {
            output: (this.input || {}),
            isSaving: false,
            isSaved: false,
            errors: [],
            currentAction: this.action,
            id: this.primaryKey,
            currentTab: null,
        };
    },
    methods: {
        getData(formfield) {
            if ((formfield.translatable || false) && !this.isObject(this.output[formfield.column.column])) {
                var value = this.output[formfield.column.column];
                this.output[formfield.column.column] = {};
                this.output[formfield.column.column][Store.locale] = value;
            }
            
            if (formfield.translatable || false) {
                return this.output[formfield.column.column][Store.locale];
            }

            return this.output[formfield.column.column];
        },
        setData(formfield, value) {
            this.getData(formfield);

            if (!this.output.hasOwnProperty(formfield.column.column)) {
                if (formfield.translatable || false) {
                    this.output[formfield.column.column] = {};
                } else {
                    this.output[formfield.column.column] = '';
                }
            }
            if (formfield.translatable || false) {
                this.output[formfield.column.column][Store.locale] = value;
            } else {
                this.output[formfield.column.column] = value;
            }
            EventBus.emit('input', {
                column: formfield.column,
                value: value,
            });
            EventBus.emit('output', this.output);
            this.$emit('output', this.output);
        },
        getErrors(column) {
            return this.errors[column.column] || [];
        },
        save(e = null) {
            if (this.isSaving || this.fromRepeater) {
                return;
            }
            if (typeof e === 'object' && e instanceof KeyboardEvent) {
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                } else {
                    return;
                }
            }
            this.isSaving = true;
            this.isSaved = false;
            let url = (this.currentAction == 'add' ? this.route('voyager.' + this.translate(this.bread.slug, true) + '.store') : this.route('voyager.' + this.translate(this.bread.slug, true) + '.update', this.id));
            axios({
                method: this.currentAction == 'add' ? 'post' : 'put',
                url: url,
                data: {
                    data: this.output,
                }
            }, this.id)
            .then((response) => {
                this.errors = [];
                if (this.currentAction == 'add') {
                    this.currentAction = 'edit';
                    this.id = response.data;

                    new this
                    .$notification(this.__('voyager::bread.type_store_success', {type: this.translate(this.bread.name_singular, true)}))
                    .color('green').timeout().show();
                } else {
                    new this
                    .$notification(this.__('voyager::bread.type_update_success', {type: this.translate(this.bread.name_singular, true)}))
                    .color('green').timeout().show();
                }
            })
            .catch((response) => {
                if (response.response.status == 422) {
                    this.errors = response.response.data;
                    new this.$notification(this.__('voyager::bread.validation_errors')).color('red').timeout().show();
                }
            })
            .then(() => {
                this.isSaving = false;
                this.isSaved = true;
            });
        },
        getComponentForType(formfield) {
            if (formfield.hasOwnProperty('component') && formfield.component !== null && formfield.component !== '') {
                if (this.$voyager.componentExists(formfield.component)) {
                    return formfield.component;
                } else {
                    console.error(this.__('voyager::generic.component_does_not_exist', { component: formfield.component, default: this.getFormfieldByType(formfield.type).component }));
                }
            }

            return this.getFormfieldByType(formfield.type).component;
        }
    },
    computed: {
        jsonOutput() {
            return Store.jsonOutput;
        }
    },
    mounted() {
        document.addEventListener('keydown', this.save);

        this.layout.formfields.forEach((formfield) => {
            var value = this.output[formfield.column.column];
            if (formfield.translatable || false) {
                value = this.output[formfield.column.column][Store.locale];
            }

            EventBus.emit('input', {
                column: formfield.column,
                value: value,
            });
        });
    },
    unmounted() {
        document.removeEventListener('keydown', this.save);
    },
};
</script>