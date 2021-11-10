<template>
    <button type="button" role="switch" @click.prevent="toggle" :class="modelValue ? `bg-${color}-500` : 'disabled'">
        <span :class="modelValue ? 'translate-x-5' : 'translate-x-0'"></span>
    </button>
</template>

<script>
export default {
    emits: ['update:modelValue'],
    props: {
        color: {
            type: String,
            default: 'accent'
        },
        modelValue: {
            type: Boolean,
            default: false,
        }
    },
    methods: {
        toggle() {
            this.$emit('update:modelValue', !this.modelValue);
        }
    }
}
</script>

<style lang="scss" scoped>
@import "@sassmixins/bg-color";
@import "@sassmixins/border-color";

.dark {
    button {
        @include border-color(toggle-border-color-dark, 'colors.gray.700');
        &.disabled {
            @include bg-color(toggle-background-color-dark, 'colors.gray.950');
        }

        span {
            @include bg-color(toggle-handle-color-dark, 'colors.gray.300');
        }
    }
}

button {
    @apply relative inline-flex flex-shrink-0 h-6 w-11 border-2 rounded-full cursor-pointer transition-colors ease-in-out duration-200;
    @include border-color(toggle-border-color, 'colors.transparent');

    &.disabled {
        @include bg-color(toggle-background-color, 'colors.gray.200');
    }

    span {
        @apply pointer-events-none inline-block h-5 w-5 rounded-full shadow transform transition ease-in-out duration-200;
        @include bg-color(toggle-handle-color, 'colors.white');
    }
}
</style>