<template>
    <input type="text" class="input w-full" :value="value" @input="input">
</template>

<script>
export default {
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        replacement: {
            default: '-'
        },
        remove: {
            default: undefined
        },
        lower: {
            type: Boolean,
            default: false
        },
        strict: {
            type: Boolean,
            default: false
        },
        dontTrim: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            value: '',
        };
    },
    methods: {
        slugifyValue(value) {
            return slugify(value, {
                replacement: this.replacement,
                remove: this.remove,
                lower: this.lower,
                strict: this.strict,
                trim: !this.dontTrim
            });
        },
        input(e) {
            this.$emit('update:modelValue', this.slugifyValue(e.target.value));
        }
    },
    created() {
        this.$watch(() => this.modelValue, () => {
            let slugged = this.slugifyValue(this.modelValue);
            this.value = slugged;
            if (slugged !== this.modelValue) {
                this.$emit('update:modelValue', slugged);
            }
        }, { immediate: true });
    }
}
</script>