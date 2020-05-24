<template>
    <div class="widgets">
        <div v-for="(widget, i) in widgets" :key="i" :class="widget.width" class="widget" v-html="widget.view">
            
        </div>
        <div v-if="widgets.length == 0" class="widget w-full">
            <h5>Welcome to Voyager II</h5>
            <p>Voyager 2 has been re-built using Laravel and VueJS. There are a lot of other cool things about version 2</p>
        </div>
    </div>
</template>
<script>
export default {
    computed: {
        widgets: function () {
            return [];
        }
    }
};
</script>
<style lang="scss" scoped>
@import "../../sass/mixins/bg-color";
@import "../../sass/mixins/border-color";
@import "../../sass/mixins/text-color";

.mode-dark .widgets {
    .widget {
        @include bg-color(widget-bg-color-dark, 'colors.gray.800');
        @include border-color(widget-border-color-dark, 'colors.gray.600');
        @include text-color(widget-text-color-dark, 'colors.gray.300');
    }
}

.widgets {
    @apply flex;

    .widget {
        @apply m-1 rounded-lg px-6 py-4 shadow-sm border;
        @include bg-color(widget-bg-color, 'colors.white');
        @include border-color(widget-border-color, 'colors.gray.200');
        @include text-color(widget-text-color, 'colors.gray.800');
    }
}
</style>