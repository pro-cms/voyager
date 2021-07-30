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
            this.getElementHeight(el).then((height) => {
                el.style.transition = `${(this.enterDuration)}ms height ease-in-out`;
                el.style.height = height+'px';
            });
        },
        beforeLeave(el) {
            el.style.height = el.scrollHeight + 'px';
            el.style.overflow = 'hidden';
        },
        leave(el) {
            if (el.scrollHeight !== 0) {
                el.style.transition = `${(this.leaveDuration)}ms height ease-in-out`;
                el.style.height = 0;
            }
        },
        afterLeave(el) {
            el.style.transition = '';
            el.style.height = '';
        }
    }
}
</script>