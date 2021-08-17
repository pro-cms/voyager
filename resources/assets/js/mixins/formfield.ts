export default {
    emits: ['update:modelValue'],
    inject: ['bread', 'relationships'],
    props: {
        action: {
            type: String,
            required: true,
            validator: function (value: string) {
                return ['query', 'browse', 'read', 'edit', 'add'].indexOf(value) >= 0;
            }
        },
        modelValue: {
            required: true,
        },
        class: {
            default: null
        },
        column: {
            type: Object,
            required: true,
        },
        options: {
            type: Object,
            required: true,
        },
        translatable: {
            type: Boolean,
            default: false,
        },
        fromRelationship: {
            type: Boolean,
            default: false,
        },
        fromRepeater: {
            type: Boolean,
            default: false,
        },
        primaryKey: {
            default: null,
        },
        placeholder: {
            type: String,
            default: '',
        },
        errors: {
            type: Array,
            default: () => []
        },
    },
};