<template>
    <input v-model="currentText">
</template>

<script>
import Store from '@/store';

export default {
    emits: ['update:modelValue'],
    props: ['modelValue'],
    data() {
        return {
            translations: {}
        };
    },
    computed: {
        currentText: {
            get() {
                return this.translations[Store.locale];
            },
            set(value) {
                this.translations[Store.locale] = value;
                this.$emit('update:modelValue', this.translations);
            }
        },
    },
    created() {
        this.$watch(() => this.modelValue, (value) => {
            this.translations = this.get_translatable_object(value);
        }, { immediate: true });
    },
};
</script>