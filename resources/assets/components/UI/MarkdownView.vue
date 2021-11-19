<template>
    <div v-html="content"></div>
</template>

<script>
export default {
    computed: {
        content() {
            return this.$slots.default()[0].children
                .replace(/^###### (.*$)/gim, '<h6 class="my-1">$1</h6>')
                .replace(/^##### (.*$)/gim, '<h5 class="my-1">$1</h5>')
                .replace(/^#### (.*$)/gim, '<h4 class="my-1">$1</h4>')
                .replace(/^### (.*$)/gim, '<h3 class="my-1">$1</h3>')
                .replace(/^## (.*$)/gim, '<h2 class="my-1">$1</h2>')
                .replace(/^# (.*$)/gim, '<h1 class="my-1">$1</h1>')
                .replace(/^\> (.*$)/gim, '<blockquote>$1</blockquote>')
                .replace(/^```([^\n ]*)(.*)```$/gims, '<pre lang="$1">$2</pre>')
                .replace(/`(.*)`/gim, '<code>$1</code>')
                .replace(/\*\*(.*)\*\*/gim, '<b>$1</b>')
                .replace(/\*(.*)\*/gim, '<i>$1</i>')
                .replace(/!\[(.*?)\]\((.*?)\)/gim, "<img alt='$1' src='$2' />")
                .replace(/\[(.*?)\]\((.*?)\)/gim, "<a href='$2' target=\"_blank\">$1</a>")
                .replace(/\n$/gim, '<br />')
                .replace(/^\< (.*$)/gim, '%lt;$1')
                .trim();
        }
    },
}
</script>