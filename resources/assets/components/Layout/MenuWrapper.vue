<template>
    <div v-for="(item, i) in items" :key="i">
        <hr class="my-3 sidebar-border" v-if="item.divider" />
        <MenuItem
            v-else
            :title="item.title"
            :icon="item.icon"
            :href="item.href"
            :badgeColor="item.badge_color"
            :badgeValue="item.badge_value"
            :active="isItemActive(item)"
            :hasChildren="item.children.length > 0"
            :iconSize="iconSize"
        >
            <div v-if="item.children.length > 0">
                <MenuWrapper
                    :items="item.children"
                />
            </div>
        </MenuItem>
    </div>
</template>

<script>
import MenuItem from '@components/Layout/MenuItem.vue';

export default {
    components: {
        MenuItem,
        'MenuWrapper': this,
    },
    props: {
        items: {
            type: Array,
            required: true,
        },
        iconSize: {
            type: Number,
            default: 6,
        }
    },
    methods: {
        isItemActive(item) {
            const reg = /.+?\:\/\/.+?(\/.+?)(?:#|\?|$)/;
            let parts = reg.exec(item.href);
            if (parts) {
                let url = parts[1].startWith('/').endWith('/');

                let current = this.$page.url.startWith('/').endWith('/');

                if (item.exact === true) {
                    return current == url;
                }

                return current.startsWith(url);
            }

            return false;
        }
    }
}
</script>