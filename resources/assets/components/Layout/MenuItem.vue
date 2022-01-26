<template>
<div class="menuitem">
    <div class="item flex items-center justify-between rounded-md font-medium mt-1 px-2 py-2" :class="[active ? 'active' : '']">
        <div class="inline-flex items-center w-full">
            <Link :href="href" class="text-sm leading-5 link inline-flex items-center space-x-2 w-full" @click="clickItem">
                <Icon v-if="icon !== '' && icon !== null" :icon="icon" class="icon" :size="iconSize" />
                <span>{{ title }}</span>
            </Link>
        </div>
        <div class="flex badge" :class="hasChildren ? 'ltr:mr-2 rtl:ml-2' : null" v-if="badgeColor !== null">
            <span v-if="badgeValue !== null" :class="`bg-${badgeColor}-500 ml-auto inline-block text-xs rounded-full py-0.5 px-3`">
                {{ isNumeric(badgeValue) ? humanReadableNumber(badgeValue) : badgeValue }}
            </span>
            <span v-else :class="`bg-${badgeColor}-500 rounded-full h-4 w-4`"></span>
        </div>
        <div class="flex-shrink-0 cursor-pointer inline-flex items-center" @click="open = !open" v-if="hasChildren">
            <Icon icon="chevron-up" :size="4" class="icon" :class="open ? 'rotate-0' : 'rotate-180'" />
        </div>
    </div>
    
    <div v-if="hasChildren" class="ltr:ml-5 rtl:mr-5">
        <CollapseTransition :duration="200">
            <slot v-if="open" />
        </CollapseTransition>
    </div>
</div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';

export default {
    components: {
        Link
    },
    props: {
        icon: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        href: {
            type: String,
            required: false,
            default: '#'
        },
        active: {
            type: Boolean,
            default: false,
        },
        isOpen: {
            type: Boolean,
            default: false,
        },
        hasChildren: {
            type: Boolean,
            default: false,
        },
        iconSize: {
            type: Number,
            default: 6,
        },
        badgeColor: {
            type: [String, null],
            default: null,
        },
        badgeValue: {
            type: [String, null],
            default: null,
        }
    },
    data() {
        return {
            open: this.isOpen,
        }
    },
    methods: {
        clickItem(e) {
            if (this.href == '' || this.href == '#') {
                e.preventDefault();
                this.open = !this.open;
            }
        }
    },
    created() {
        this.$watch(() => this.active, (active) => {
            this.open = active;
        }, { immediate: true })
    }
};
</script>

<style lang="scss" scoped>
@import "@sassmixins/text-color";

.dark .menuitem .item .badge {
    @include text-color(badge-text-color-dark, 'colors.white');
}

.menuitem .item .badge {
    @include text-color(badge-text-color, 'colors.white');
}
</style>