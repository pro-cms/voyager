<template>
    <component :is="tag" @dragover.prevent.stop>
        <transition-group name="draggable">
            <Item
                v-for="(item, i) in modelValue"
                :key="`item-${item[index]}`"
                :item="item"
                @update:item="$emit('update:modelValue', items)"
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
                <slot name="item" :item="item" :index="i"></slot>
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
            items: this.modelValue,
            draggingIndex: null,
            dontEmit: true,
        };
    },
    methods: {
        onDragStart(id) {
            this.draggingIndex = id;
        },
        onDragEnd(id) {
            this.draggingIndex = null;
        },
        onDragOver(id) {
            let items = this.modelValue;
            let to = items.indexOfProp(this.index, id);
            let from = items.indexOfProp(this.index, this.draggingIndex);

            if (to != undefined && from != undefined && to !== from) {
                items.move(from, to);
                this.$emit('update:modelValue', items);
            }
        },
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