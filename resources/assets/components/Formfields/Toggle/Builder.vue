<template>
    <div v-if="action == 'list-options'">
        <div class="input-group mt-2">
            <label class="label">{{ __('voyager::formfields.toggle.enabled_label') }}</label>
            <LanguageInput
                class="input w-full"
                type="text" :placeholder="__('voyager::formfields.toggle.enabled_label')"
                v-model="options.enabled_label" />
        </div>
        <div class="input-group mt-2">
            <label class="label">{{ __('voyager::formfields.toggle.disabled_label') }}</label>
            <LanguageInput
                class="input w-full"
                type="text" :placeholder="__('voyager::formfields.toggle.disabled_label')"
                v-model="options.disabled_label" />
        </div>
    </div>
    <div v-else-if="action == 'view-options'">
        <div class="input-group mt-2">
            <label class="label">{{ __('voyager::generic.color') }}</label>
            <ColorPicker v-model="options.color" />
        </div>
    </div>
    <div v-else-if="action == 'view'" class="w-full">
        <Toggle :color="options.color" v-model="dummy" />
    </div>
</template>

<script>
import formfieldBuilder from '@mixins/formfield-builder';

export default {
    mixins: [formfieldBuilder],
    computed: {
        defaultViewOptions() {
            return {
                color: 'accent',
            };
        },
        defaultListOptions() {
            return {
                enabled_label: this.__('voyager::generic.yes'),
                disabled_label: this.__('voyager::generic.no'),
            };
        },
    },
    data() {
        return {
            dummy: false,
        }
    }
}
</script>