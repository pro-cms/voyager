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
            el.style.width = 'auto';
        },
        enter(element) {
            const { height } = getComputedStyle(element);
            element.style.height = height;
            element.style.position = 'absolute';
            element.style.visibility = 'hidden';
            element.style.width = 'auto';
            const { width } = getComputedStyle(element);
            element.style.height = null;
            element.style.position = null;
            element.style.visibility = null;
            element.style.width = 0;
            getComputedStyle(element).width;
            requestAnimationFrame(() => {
                element.style.width = width;
            });
        },
        leave(element) {
            const { width } = getComputedStyle(element);
            element.style.width = width;
            getComputedStyle(element).width;
            requestAnimationFrame(() => {
                element.style.width = 0;
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
            return `width ${this.entering ? this.enterDuration : this.leaveDuration}ms ease-in-out`
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
    will-change: width;
    backface-visibility: hidden;
}

.collapse-enter-active, .collapse-leave-active {
    transition: v-bind(transitionStyle);
    overflow: hidden;
}
.collapse-enter, .collapse-leave-to {
    width: 0;
}
</style>