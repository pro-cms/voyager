<template>
    <component
        :is="tag"
        @dragover.prevent.stop="onDragOver"
        @dragstart.stop="onDragStart"
        @dragend.stop="onDragEnd"
        @dragenter.prevent
        v-bind="attributes"
        :class="this.transitioning ? this.dragClass : null"
    >
        <slot />
    </component>
</template>

<script>
import debounce from 'debounce';

export default {
    emits: ['onDragOver', 'onDragStart', 'onDragEnd'],
    props: {
        item: {
            type: Object,
        },
        index: {
            type: String,
            required: true,
        },
        handle: {
            type: [String, null],
            default: null,
        },
        dragClass: {
            type: String
        },
        tag: {
            type: String,
        },
        callback: {
            type: Function,
        },
    },
    data() {
        return {
            transitioning: false,
        };
    },
    methods: {
        onDragStart(e) {
            this.transitioning = true;
            this.$emit('onDragStart', this.item[this.index]);
        },
        onDragEnd(e) {
            this.transitioning = false;
            this.$emit('onDragEnd', this.item[this.index]);
        },
        onDragOver: debounce(function (e) {
            this.$emit('onDragOver', this.item[this.index]);
        }, 25, true),
        makeDraggable() {
            this.$el.draggable = true;
        },
        makeUndraggable() {
            this.$el.draggable = false;
        }
    },
    computed: {
        attributes() {
            if (this.callback) {
                return this.callback(this.item);
            }

            return {};
        }
    },
    mounted() {
        if (this.handle !== null) {
            this.$el.querySelectorAll(this.handle).forEach(el => {
                el.addEventListener('mousedown', this.makeDraggable);
                el.addEventListener('mouseup', this.makeUndraggable);
            });
        } else {
            this.makeDraggable();
        }
    },
    unmounted() {
        if (this.handle !== null) {
            this.$el.querySelectorAll(this.handle).forEach(el => {
                el.removeEventListener('mousedown', this.makeDraggable);
                el.removeEventListener('mouseup', this.makeUndraggable);
            });
        }
    }
};
</script>