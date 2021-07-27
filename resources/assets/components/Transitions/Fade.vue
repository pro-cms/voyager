<template>
    <transition v-if="!group" name="fade" @enter="enter" @leave="leave">
        <slot />
    </transition>
    <transition-group v-else name="fade" @enter="enter" @leave="leave">
        <slot />
    </transition-group>
</template>

<script>
import base from './base';

export default {
    mixins: [base],
    methods: {
        enter(el) {
            el.style.transition = `opacity ${this.enterDuration}ms ease`;
        },
        leave(el) {
            el.style.transition = `opacity ${this.leaveDuration}ms ease`;
        }
    }
}
</script>

<style scoped>
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>