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
        orgoptions: {
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
        },
    },
    computed: {
        defaultListOptions() {
            return {};
        },
        defaultViewOptions() {
            return {};
        },
        options: {
            get() {
                if (this.defaultListOptions && this.action == 'list-options') {
                    return { ...this.defaultListOptions, ...this.orgoptions };
                }
                return { ...this.defaultViewOptions, ...this.orgoptions };
            },
            set(options: Object) {
                this.$emit('update:options', options);
            }
        }
    },
});