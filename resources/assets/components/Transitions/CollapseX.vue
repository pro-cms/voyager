<template>
    <transition
        v-if="!group"
        name="collapse"
        @enter="enter"
        @before-leave="beforeLeave"
        @leave="leave"
        @after-leave="afterLeave"
    >
        <slot></slot>
    </transition>
    <transition-group
        v-else
        name="collapse"
        @enter="enter"
        @before-leave="beforeLeave"
        @leave="leave"
        @after-leave="afterLeave"
    >
        <slot></slot>
    </transition-group>
</template>

<script>
import base from './base';

export default {
    mixins: [base],
    methods: {
        enter(el) {
            this.getElementWidth(el).then((width) => {
                el.style.transition = `${(this.enterDuration)}ms width ease-in-out`;
                el.style.width = width+'px';
            });
        },
        beforeLeave(el) {
            el.style.width = el.scrollWidth + 'px';
            el.style.overflow = 'hidden';
        },
        leave(el) {
            if (el.scrollWidth !== 0) {
                el.style.transition = `${(this.leaveDuration)}ms width ease-in-out`;
                el.style.width = 0;
            }
        },
        afterLeave(el) {
            el.style.transition = '';
            el.style.width = '';
        }
    }
}
</script>