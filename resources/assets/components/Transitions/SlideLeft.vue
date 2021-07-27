<template>
    <transition
        v-if="!group"
        enter-active-class="slide-in" leave-active-class="slide-out"
        @before-enter="beforeEnter"
        @before-leave="beforeLeave"
    >
        <slot></slot>
    </transition>
    <transition-group
        v-else
        enter-active-class="slide-in" leave-active-class="slide-out"
        @before-enter="beforeEnter"
        @before-leave="beforeLeave"
    >
        <slot></slot>
    </transition-group>
</template>

<script>
import base from './base';

export default {
    mixins: [base],
    methods: {
        beforeEnter(el) {
            el.style.animationDuration = `${this.enterDuration}ms`;
        },
        beforeLeave(el) {
            el.style.animationDuration = `${this.leaveDuration}ms`;
        },
    }
}
</script>

<style lang="scss" scoped>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
    }
}
.slide-in {
    animation-name: slideIn;
}

@keyframes slideOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
        transform: translateX(30px);
    }
}
.slide-out {
    animation-name: slideOut;
}
</style>