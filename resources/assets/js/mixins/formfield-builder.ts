import { defineComponent } from 'vue';

export default defineComponent({
    emits: ['update:options'],
    props: {
        action: {
            type: String,
            required: true,
            validator: function (value: string) {
                return ['view', 'view-options', 'list-options'].indexOf(value) >= 0;
            }
        },
        options: {
            type: Object,
            required: true,
        },
        column: {
            type: Object,
            required: true,
        },
        columns: {
            type: Array,
            default: () => [],
        },
        relationships: {
            type: Array,
            default: () => [],
        }
    },
    created: function () {
        // Merge default options into options.
        // This is useful when adding options at a later time, so code won't fail because props don't exist.

        // @ts-ignore
        if (this.defaultListOptions && this.action == 'list-options') {
            // @ts-ignore
            this.$emit('update:options', Object.assign({ ...this.defaultListOptions, ...this.options }));
            // @ts-ignore
        } else if (this.defaultViewOptions && this.action == 'view-options') {
            // @ts-ignore
            this.$emit('update:options', Object.assign({ ...this.defaultViewOptions, ...this.options }));
        }
    }
});