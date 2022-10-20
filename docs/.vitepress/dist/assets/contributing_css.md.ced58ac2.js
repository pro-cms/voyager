import{_ as e,c as o,o as r,a}from"./app.e6a18615.js";const g='{"title":"CSS","description":"","frontmatter":{},"headers":[{"level":2,"title":"Mixins","slug":"mixins"},{"level":3,"title":"Background color","slug":"background-color"},{"level":3,"title":"Text color","slug":"text-color"},{"level":3,"title":"Border color","slug":"border-color"},{"level":3,"title":"Note","slug":"note"}],"relativePath":"contributing/css.md"}',l={},s=a(`<h1 id="css" tabindex="-1">CSS <a class="header-anchor" href="#css" aria-hidden="true">#</a></h1><h2 id="mixins" tabindex="-1">Mixins <a class="header-anchor" href="#mixins" aria-hidden="true">#</a></h2><p>We use a variety of mixins for all kind of colors (border, text, background ...)<br> Those mixins easily generate a CSS variable which can be overriden by theme plugins.</p><h3 id="background-color" tabindex="-1">Background color <a class="header-anchor" href="#background-color" aria-hidden="true">#</a></h3><div class="language-"><pre><code>@import &quot;@sassmixins/bg-color&quot;;

.myclass {
    @include bg-color(my-class-background, &#39;colors.gray.500&#39;);
}
</code></pre></div><h3 id="text-color" tabindex="-1">Text color <a class="header-anchor" href="#text-color" aria-hidden="true">#</a></h3><div class="language-"><pre><code>@import &quot;@sassmixins/text-color&quot;;

.myclass {
    @include text-color(my-class-text-color, &#39;colors.gray.500&#39;);
}
</code></pre></div><h3 id="border-color" tabindex="-1">Border color <a class="header-anchor" href="#border-color" aria-hidden="true">#</a></h3><div class="language-"><pre><code>@import &quot;@sassmixins/border-color&quot;;

.myclass {
    @include border-color(my-class-border, &#39;colors.gray.500&#39;);
}
</code></pre></div><h3 id="note" tabindex="-1">Note <a class="header-anchor" href="#note" aria-hidden="true">#</a></h3><p>As a rule of thumb: you should never directly include a color, for example <code>color: red;</code>.<br> Instead use the appropriate mixin, give it a reasonable name and provide a default value.<br> For example:</p><div class="language-"><pre><code>@import &quot;sassmixins/bg-color&quot;;
@import &quot;sassmixins/text-color&quot;;

.body {
    @include bg-color(bg-color, &#39;colors.gray.500&#39;);
    @include text-color(text-color, &#39;colors.red.500&#39;);
}
</code></pre></div><p>This will allow theme-developers to override your colors.</p>`,13),c=[s];function t(i,n,d,u,h,p){return r(),o("div",null,c)}var m=e(l,[["render",t]]);export{g as __pageData,m as default};
