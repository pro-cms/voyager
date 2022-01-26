<template>
    <span
        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium leading-4 transition ease-in-out duration-150 select-none"
        :class="[`button ${color}`, large ? 'px-3 text-sm leading-5' : null]"
    >
        <slot></slot>
        <Icon
            v-on:click="$emit('click-icon', $event)"
            class="cursor-pointer ml-1"
            :size="large ? 5 : 4"
            v-if="icon !== null"
            :icon="icon"
        />
    </span>
</template>
<script>
export default {
    emits: ['click-icon'],
    props: {
        color: {
            type: String,
            default: 'accent',
        },
        icon: {
            type: [String, null],
            default: null,
        },
        large: {
            type: Boolean,
            default: false,
        }
    },
};
</script>