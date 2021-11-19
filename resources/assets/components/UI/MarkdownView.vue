<template>
    <div class="markdown" v-html="content"></div>
</template>

<script>
import { marked } from 'marked';

const renderer = {
    heading(text, level) {
        return `
            <h${level} class="mb-1.5">
            ${text}
            </h${level}>`;
    }
};

marked.use({ renderer });

export default {
    computed: {
        content() {
            return marked.parse(this.$slots.default()[0].children, { gfm: true });
        }
    },
}
</script>