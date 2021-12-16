import{_ as n,c as s,o as a,a as t}from"./app.0fbab82a.js";const m='{"title":"Routes","description":"","frontmatter":{},"headers":[{"level":2,"title":"Protected","slug":"protected"},{"level":2,"title":"Frontend","slug":"frontend"}],"relativePath":"plugins/routes.md","lastUpdated":1639647582437}',p={},e=t(`<h1 id="routes" tabindex="-1">Routes <a class="header-anchor" href="#routes" aria-hidden="true">#</a></h1><h2 id="protected" tabindex="-1">Protected <a class="header-anchor" href="#protected" aria-hidden="true">#</a></h2><p>Protected routes can only be accessed by users logged in to Voyager.<br> To implement protected routes into your plugin implement the <code>ProtectedRoutes</code> trait and write a method <code>provideProtectedRoutes</code> registering your routes:</p><div class="language-php"><pre><code><span class="token php language-php"><span class="token delimiter important">&lt;?php</span>

<span class="token keyword">use</span> <span class="token package">Illuminate<span class="token punctuation">\\</span>Support<span class="token punctuation">\\</span>Facades<span class="token punctuation">\\</span>Route</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Inertia<span class="token punctuation">\\</span>Inertia</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Contracts<span class="token punctuation">\\</span>Plugins<span class="token punctuation">\\</span>Features<span class="token punctuation">\\</span>Provider<span class="token punctuation">\\</span>ProtectedRoutes</span><span class="token punctuation">;</span>

<span class="token keyword">class</span> <span class="token class-name-definition class-name">MyPlugin</span> <span class="token keyword">implements</span> <span class="token class-name">ProtectedRoutes</span>
<span class="token punctuation">{</span>
    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">provideProtectedRoutes</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token keyword return-type">void</span>
    <span class="token punctuation">{</span>
        <span class="token class-name static-context">Route</span><span class="token operator">::</span><span class="token function">get</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;/my-page&#39;</span><span class="token punctuation">,</span> <span class="token keyword">function</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
            <span class="token keyword">return</span> <span class="token class-name static-context">Inertia</span><span class="token operator">::</span><span class="token function">render</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;component-to-render&#39;</span><span class="token punctuation">,</span> <span class="token punctuation">[</span>
                <span class="token string single-quoted-string">&#39;foo&#39;</span>   <span class="token operator">=&gt;</span> <span class="token string single-quoted-string">&#39;bar&#39;</span><span class="token punctuation">,</span>
            <span class="token punctuation">]</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">withViewData</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;title&#39;</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">&#39;My page&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">name</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-page&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>

        <span class="token class-name static-context">Route</span><span class="token operator">::</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;/my-page&#39;</span><span class="token punctuation">,</span> <span class="token keyword">function</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
            <span class="token comment">// Do something</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">name</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-page&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</span></code></pre></div><div class="info custom-block"><p class="custom-block-title">INFO</p><p>This example shows an Inertia response that will show a Vue component inside Voyagers master-view.<br> However, you can return whatever you want, a blade-view for example.</p></div><h2 id="frontend" tabindex="-1">Frontend <a class="header-anchor" href="#frontend" aria-hidden="true">#</a></h2><p>Frontend routes can be accessed by everyone. You don&#39;t have to be logged in to Voyager to access those pages.<br> To implement protected routes into your plugin implement the <code>FrontendRoutes</code> trait and write a method <code>provideFrontendRoutes</code> registering your routes:</p><div class="language-php"><pre><code><span class="token php language-php"><span class="token delimiter important">&lt;?php</span>

<span class="token keyword">use</span> <span class="token package">Illuminate<span class="token punctuation">\\</span>Support<span class="token punctuation">\\</span>Facades<span class="token punctuation">\\</span>Route</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Inertia<span class="token punctuation">\\</span>Inertia</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Contracts<span class="token punctuation">\\</span>Plugins<span class="token punctuation">\\</span>Features<span class="token punctuation">\\</span>Provider<span class="token punctuation">\\</span>FrontendRoutes</span><span class="token punctuation">;</span>

<span class="token keyword">class</span> <span class="token class-name-definition class-name">MyPlugin</span> <span class="token keyword">implements</span> <span class="token class-name">FrontendRoutes</span>
<span class="token punctuation">{</span>
    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">provideFrontendRoutes</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token keyword return-type">void</span>
    <span class="token punctuation">{</span>
        <span class="token class-name static-context">Route</span><span class="token operator">::</span><span class="token function">get</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;/my-page&#39;</span><span class="token punctuation">,</span> <span class="token keyword">function</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
            <span class="token keyword">return</span> <span class="token function">view</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-view&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">name</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-page&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>

        <span class="token class-name static-context">Route</span><span class="token operator">::</span><span class="token function">post</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;/my-page&#39;</span><span class="token punctuation">,</span> <span class="token keyword">function</span> <span class="token punctuation">(</span><span class="token punctuation">)</span> <span class="token punctuation">{</span>
            <span class="token comment">// Do something</span>
        <span class="token punctuation">}</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">name</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-page&#39;</span><span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</span></code></pre></div><div class="info custom-block"><p class="custom-block-title">INFO</p><p>You are not able to display a component/view inside Voyagers master-view as this uses some data that is not available when the user is not logged-in.</p><div class="info custom-block"><p class="custom-block-title">INFO</p></div></div>`,9),o=[e];function c(u,i,l,r,k,d){return a(),s("div",null,o)}var y=n(p,[["render",c]]);export{m as __pageData,y as default};
