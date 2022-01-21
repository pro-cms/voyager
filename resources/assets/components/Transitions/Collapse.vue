<template>
    <transition
        v-if="!group"
        name="collapse"
        @before-enter="beforeEnter"
        @enter="enter"
        @after-enter="afterEnter"
        @before-leave="beforeLeave"
        @leave="leave"
    >
        <slot></slot>
    </transition>
    <transition-group
        v-else
        name="collapse"
        @before-enter="beforeEnter"
        @enter="enter"
        @after-enter="afterEnter"
        @before-leave="beforeLeave"
        @leave="leave"
    >
        <slot></slot>
    </transition-group>
</template>

<script>
import base from './base';

export default {
    mixins: [base],
    methods: {
        afterEnter(el) {
            el.style.height = 'auto';
        },
        enter(element) {
            const { width } = getComputedStyle(element);
            element.style.width = width;
            element.style.position = 'absolute';
            element.style.visibility = 'hidden';
            element.style.height = 'auto';
            const { height } = getComputedStyle(element);
            element.style.width = null;
            element.style.position = null;
            element.style.visibility = null;
            element.style.height = 0;
            getComputedStyle(element).height;
            requestAnimationFrame(() => {
                element.style.height = height;
            });
        },
        leave(element) {
            const { height } = getComputedStyle(element);
            element.style.height = height;
            getComputedStyle(element).height;
            requestAnimationFrame(() => {
                element.style.height = 0;
            });
        },
        beforeEnter() {
            this.entering = true;
        },
        beforeLeave() {
            this.entering = false;
        }
    },
    computed: {
        transitionStyle() {
            return `height ${this.entering ? this.enterDuration : this.leaveDuration}ms ease-in-out`
        }
    },
    data() {
        return {
            entering: true,
        };
    }
}
</script>

<style scoped>
* {
    will-change: height;
    backface-visibility: hidden;
}

.collapse-enter-active, .collapse-leave-active {
    transition: v-bind(transitionStyle);
    overflow: hidden;
}
.collapse-enter, .collapse-leave-to {
    height: 0;
}
</style>