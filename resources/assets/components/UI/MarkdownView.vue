<template>
    <div>
        <div class="hidden">
            <Alert color="green" id="hint_info" class="my-2"></Alert>
            <Alert color="yellow" id="hint_warning" class="my-2"></Alert>
        </div>
        <div class="markdown" v-html="content"></div>
    </div>
</template>

<script>
import { marked } from 'marked';

const alerts = {
    name: 'alerts',
    level: 'inline',
    start(src) { return src.match(/{% hint/)?.index; },
    tokenizer(src, tokens) {
        const rule = /{% hint style=\"(.*)\" %}(.*){% endhint %}/gmis;
        const match = rule.exec(src);
        if (match) {
            return {
                type: 'alerts',
                raw: match[0],
                hint_type: this.lexer.inlineTokens(match[1].trim()),
                text: this.lexer.inlineTokens(match[2].trim()),
            };
        }
    },
    renderer(token) {
        return `<div class="hint_${this.parser.parseInline(token.hint_type)}">${this.parser.parseInline(token.text)}</div>`;
    },
};

export default {
    props: {
        options: {
            type: Object,
            default: () => {},
        },
        renderer: {
            type: [Object, null],
            default: null,
        },
    },
    computed: {
        content() {
            return marked.parse(this.$slots.default()[0].children, { ...this.options, gfm: true  });
        }
    },
    methods: {
        use(extensions) {
            marked.use(extensions);
        }
    },
    created() {
        marked.use({
            renderer: this.renderer || {
                heading(text, level) {
                    return `<h${level} class="mb-1.5">${text}</h${level}>`;
                }
            }
        });
        marked.use({ extensions: [alerts] });
    },
    mounted() {
        this.$el.querySelectorAll('div .hint_info, div .hint_warning').forEach((el) => {
            let alert = document.getElementById(el.classList[0]).cloneNode(true);
            alert.removeAttribute("id");
            alert.children[0].children[1].innerHTML = el.innerHTML;
            el.innerHTML = alert.outerHTML;
        });
    },
}
</script>