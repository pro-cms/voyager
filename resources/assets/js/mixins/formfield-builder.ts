import { defineComponent, reactive } from 'vue';

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
        },
        class: {
            default: null
        },
    },
    computed: {
        defaultListOptions() {
            return {};
        },
        defaultViewOptions() {
            return {};
        },
    },
    methods: {
        mergeOptions() {
            let options = {};
            if (this.defaultListOptions && this.action == 'list-options') {
                options = reactive({ ...this.defaultListOptions, ...this.options });
            } else {
                options = reactive({ ...this.defaultViewOptions, ...this.options });
            }

            let key, value;
            for ([key, value] of Object.entries(options)) {
                this.options[key] = value;
            }

            this.$emit('update:options', options);
        }
    },
    created() {
        this.mergeOptions();
    }
});