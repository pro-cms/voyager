import{_ as n,c as s,o as a,a as t}from"./app.e6a18615.js";const g='{"title":"Assets","description":"","frontmatter":{},"headers":[{"level":2,"title":"Javascript","slug":"javascript"},{"level":2,"title":"CSS","slug":"css"},{"level":2,"title":"Example","slug":"example"}],"relativePath":"plugins/assets.md"}',e={},o=t(`<h1 id="assets" tabindex="-1">Assets <a class="header-anchor" href="#assets" aria-hidden="true">#</a></h1><p>Chances are very high that you want your plugin to be able to provide Javascript and/or CSS.<br> This can be done by implementing a contract and providing a method returning your assets as plain text.<br> Directly providing your assets allows you to simply develop your plugin and releasing it - without the need to re-publish files with any change.<br> Don&#39;t worry - Voyager takes care of caching your assets.</p><h2 id="javascript" tabindex="-1">Javascript <a class="header-anchor" href="#javascript" aria-hidden="true">#</a></h2><p>Implement the contract <code>Voyager\\Admin\\Contracts\\Features\\Features\\Provider\\JS</code> and provide a method <code>provideJS()</code> returning a string containing your Javascript code.</p><div class="info custom-block"><p class="custom-block-title">INFO</p><p>A formfield plugin automatically implements the JS contract.<br> You don&#39;t need to do this manually!</p></div><p>Read more about public Javascript APIs <a href="./../javascript.html">here</a></p><h2 id="css" tabindex="-1">CSS <a class="header-anchor" href="#css" aria-hidden="true">#</a></h2><p>Implement the contract <code>Voyager\\Admin\\Contracts\\Features\\Features\\Provider\\CSS</code> and provide a method <code>provideCSS()</code> returning a string containing your CSS.</p><h2 id="example" tabindex="-1">Example <a class="header-anchor" href="#example" aria-hidden="true">#</a></h2><div class="language-php"><pre><code><span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Contracts<span class="token punctuation">\\</span>Plugins<span class="token punctuation">\\</span>GenericPlugin</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> Voyager\\Admin\\Contracts\\Plugins\\Features\\Provider\\<span class="token punctuation">{</span><span class="token constant">CSS</span><span class="token punctuation">,</span> <span class="token constant">JS</span><span class="token punctuation">}</span><span class="token punctuation">;</span>

<span class="token keyword">class</span> <span class="token class-name-definition class-name">VoyagerDocs</span> <span class="token keyword">implements</span> <span class="token class-name">GenericPlugin</span><span class="token punctuation">,</span> <span class="token constant">CSS</span><span class="token punctuation">,</span> <span class="token constant">JS</span>
<span class="token punctuation">{</span>
    <span class="token comment">// ...</span>

    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">provideCSS</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token keyword return-type">string</span>
    <span class="token punctuation">{</span>
        <span class="token keyword">return</span> <span class="token function">file_get_contents</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;path/to/your/asset.css&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>

    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">provideJS</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token keyword return-type">string</span>
    <span class="token punctuation">{</span>
        <span class="token keyword">return</span> <span class="token function">file_get_contents</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;path/to/your/asset.js&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</code></pre></div>`,10),p=[o];function c(i,r,l,u,d,k){return a(),s("div",null,p)}var m=n(e,[["render",c]]);export{g as __pageData,m as default};
