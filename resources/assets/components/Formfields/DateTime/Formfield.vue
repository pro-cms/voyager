<template>
    <div v-if="action == 'query'">
        <slot />
    </div>
    <div v-else-if="action == 'browse'">
        {{ dayjs(modelValue).format(options.displayFormat) }}
    </div>
    <template v-else-if="action == 'edit' || action == 'add'">
        <DateTime v-bind="options" :modelValue="modelValue" @update:modelValue="$emit('update:modelValue', $event)" />
    </template>
    <template>
        <DateTime v-bind="options" />
    </template>
</template>

<script>
import formfield from '@mixins/formfield';
import { dayjs } from '../../UI/DateTime.vue';

export default {
    mixins: [formfield],
    methods: {
        dayjs
    }
}
</script>