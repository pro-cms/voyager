<template>
    <div v-if="action == 'query'">
        <!-- -->
    </div>
    <template v-else-if="action == 'browse' || action == 'read'">
        {{ translate((value ? options.enabled_label : options.disabled_label), true) }}
    </template>
    <template v-else-if="action == 'edit' || action == 'add'">
        <Toggle v-model="value" :color="options.color" />
    </template>
</template>

<script>
import formfield from '@mixins/formfield';

export default {
    mixins: [formfield],
    computed: {
        value: {
            get() {
                return this.boolVal(this.modelValue);
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
}
</script>