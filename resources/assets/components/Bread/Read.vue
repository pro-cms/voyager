<template>
    <div>
        <Card :title="__('voyager::bread.read_type', { type: translate(bread.name_singular, true) })" :icon="bread.icon">
            <template #actions>
                <div class="flex flex-wrap items-center space-x-1">
                    <a class="button small" v-if="prevUrl !== ''" :href="prevUrl">
                        <Icon icon="chevron-left" />
                        <span>{{ __('voyager::generic.back') }}</span>
                    </a>
                    <LocalePicker />
                </div>
            </template>
            <div class="tabs mb-4" v-if="layout.tabs.length > 0">
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
                    <div v-if="formfield.tab === currentTab" class="m-0" :class="formfield.options.width">
                        <Card :title="translate(formfield.options.title, true)" :title-size="5" :show-title="translate(formfield.options.label, true) !== ''">
                            <div>
                                <component
                                    :is="getFormfieldByType(formfield.type).component"
                                    :modelValue="getData(formfield)"
                                    :bread="bread"
                                    :options="formfield.options"
                                    :column="formfield.column"
                                    :relationships="relationships"
                                    :translatable="formfield.translatable"
                                    :primary-key="primary"
                                    :class="formfield.options.classes"
                                    action="read" />
                                <p class="description" v-if="translate(formfield.options.description, true) !== ''">
                                    {{ translate(formfield.options.description, true) }}
                                </p>
                            </div>
                        </Card>
                    </div>
                </template>
            </div>
        </Card>
    </div>
</template>

<script>
export default {
    props: ['bread', 'data', 'primary', 'layout', 'prevUrl', 'relationships'],
    data() {
        return {
            currentTab: null,
        };
    },
    methods: {
        getData(formfield) {
            if (formfield.translatable || false) {
                return this.data[formfield.column.column][this.$store.locale] || '';
            }

            return this.data[formfield.column.column];
        }
    },
};
</script>