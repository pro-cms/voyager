<template>
    <component :is="tag" @dragover.prevent.stop>
        <transition-group name="draggable">
            <Item
                v-for="item in items"
                :key="`item-${item[index]}`"
                :item="item"
                :index="index"
                :handle="handle"
                :tag="itemTag"
                :dragClass="dragClass"
                :callback="itemAttrs"
                @onDragStart="onDragStart"
                @onDragEnd="onDragEnd"
                @onDragOver="onDragOver"
                @dragenter.prevent
            >
                <slot name="item" :item="item"></slot>
            </Item>
        </transition-group>
    </component>
</template>

<script>
import Item from './DragItem.vue';

export default {
    components: { Item },
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: Array,
            required: true
        },
        index: {
            type: String,
            default: 'id'
        },
        handle: {
            type: String,
            default: null,
        },
        transition: {
            type: Number,
            default: 100,
        },
        dragClass: {
            type: String,
            default: 'opacity-75'
        },
        tag: {
            type: String,
            default: 'div'
        },
        itemTag: {
            type: String,
            default: 'div'
        },
        itemAttrs: {
            type: Function,
            default: null,
        }
    },
    data() {
        return {
            items: [],
            draggingIndex: null,
        };
    },
    methods: {
        onDragStart(id) {
            this.draggingIndex = id;
        },
        onDragEnd(id) {
            this.draggingIndex = null;
            this.$emit('update:modelValue', this.items);
        },
        onDragOver(id) {
            let to = this.items.indexOfProp(this.index, id);
            let from = this.items.indexOfProp(this.index, this.draggingIndex);

            if (to != undefined && from != undefined && to !== from) {
                this.items.move(from, to);
            }
        },
    },
    mounted() {
        this.$watch(() => this.modelValue, () => {
            this.items = JSON.parse(JSON.stringify(this.modelValue));
        }, { immediate: true, deep: true });
    },
    computed: {
        transitionStyle() {
            return `transform ${this.transition}ms`;
        }
    }
}
</script>

<style scoped>
.draggable-move {
    transition: v-bind(transitionStyle);
}
</style>