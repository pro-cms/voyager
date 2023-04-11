import{_ as n,c as s,o as a,a as t}from"./app.e6a18615.js";const g='{"title":"Menu Items","description":"","frontmatter":{},"headers":[{"level":2,"title":"Available methods","slug":"available-methods"},{"level":2,"title":"User dropdown","slug":"user-dropdown"}],"relativePath":"plugins/menu-items.md"}',e={},o=t(`<h1 id="menu-items" tabindex="-1">Menu Items <a class="header-anchor" href="#menu-items" aria-hidden="true">#</a></h1><p>You can inject menu items to the menu by simply implementing the <code>MenuItems</code> provider and adding a method <code>provideMenuItems</code> to your plugin like this:</p><div class="language-php"><pre><code><span class="token php language-php"><span class="token delimiter important">&lt;?php</span>

<span class="token keyword">namespace</span> <span class="token package">Me<span class="token punctuation">\\</span>MyPlugin</span><span class="token punctuation">;</span>

<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Classes<span class="token punctuation">\\</span>MenuItem</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Contracts<span class="token punctuation">\\</span>Plugins<span class="token punctuation">\\</span>GenericPlugin</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Contracts<span class="token punctuation">\\</span>Plugins<span class="token punctuation">\\</span>Features<span class="token punctuation">\\</span>Provider<span class="token punctuation">\\</span>MenuItems</span><span class="token punctuation">;</span>
<span class="token keyword">use</span> <span class="token package">Voyager<span class="token punctuation">\\</span>Admin<span class="token punctuation">\\</span>Manager<span class="token punctuation">\\</span>Menu</span> <span class="token keyword">as</span> MenuManager<span class="token punctuation">;</span>

<span class="token keyword">class</span> <span class="token class-name-definition class-name">MyPlugin</span> <span class="token keyword">implements</span> <span class="token class-name">GenericPlugin</span><span class="token punctuation">,</span> MenuItems
<span class="token punctuation">{</span>
    <span class="token keyword">public</span> <span class="token keyword">function</span> <span class="token function-definition function">provideMenuItems</span><span class="token punctuation">(</span><span class="token class-name type-declaration">Menu</span> <span class="token variable">$menuManager</span><span class="token punctuation">)</span><span class="token punctuation">:</span> <span class="token keyword return-type">void</span> <span class="token punctuation">{</span>
        <span class="token variable">$menumanager</span><span class="token operator">-&gt;</span><span class="token function">addItems</span><span class="token punctuation">(</span>
            <span class="token punctuation">(</span><span class="token keyword">new</span> <span class="token class-name">MenuItem</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;My Title&#39;</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">&#39;icon&#39;</span><span class="token punctuation">)</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">route</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-route&#39;</span><span class="token punctuation">)</span>
        <span class="token punctuation">)</span><span class="token punctuation">;</span>
    <span class="token punctuation">}</span>
<span class="token punctuation">}</span>
</span></code></pre></div><p>You can also add a divider before or after your item like this:</p><div class="language-php"><pre><code><span class="token variable">$menumanager</span><span class="token operator">-&gt;</span><span class="token function">addItems</span><span class="token punctuation">(</span>
    <span class="token punctuation">(</span><span class="token keyword">new</span> <span class="token class-name">MenuItem</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">divider</span><span class="token punctuation">(</span><span class="token punctuation">)</span><span class="token punctuation">,</span>
    <span class="token punctuation">(</span><span class="token keyword">new</span> <span class="token class-name">MenuItem</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;My Title&#39;</span><span class="token punctuation">,</span> <span class="token string single-quoted-string">&#39;icon&#39;</span><span class="token punctuation">)</span><span class="token punctuation">)</span><span class="token operator">-&gt;</span><span class="token function">route</span><span class="token punctuation">(</span><span class="token string single-quoted-string">&#39;my-route&#39;</span><span class="token punctuation">)</span>
<span class="token punctuation">)</span><span class="token punctuation">;</span>
</code></pre></div><h2 id="available-methods" tabindex="-1">Available methods <a class="header-anchor" href="#available-methods" aria-hidden="true">#</a></h2><table><thead><tr><th><strong>Method</strong></th><th><strong>Description</strong></th><th>Example</th><th><strong>Arguments</strong></th></tr></thead><tbody><tr><td>__construct</td><td>Creates a new Menu item</td><td><code>new MenuItem(&#39;My title&#39;);</code></td><td>string title: The title string icon: The name of an icon</td></tr><tr><td>route</td><td>A route to be used</td><td><code>-&gt;route(&#39;my.route.name&#39;)</code></td><td>string route: The route key array params: The parameters passed to the route</td></tr><tr><td>url</td><td>A URL to be used</td><td><code>-&gt;url(&#39;https://google.com&#39;)</code></td><td>string url: The URL</td></tr><tr><td>permission</td><td>Display/Hide the item based on a permission</td><td><code>-&gt;permission(&#39;name_of_permission&#39;)</code></td><td>string permission: The key of a permission, array args: Additional arguments</td></tr><tr><td>divider</td><td>Acts as a divider between items</td><td><code>-&gt;divider()</code></td><td>-</td></tr><tr><td>exact</td><td>Apply the active class only when the current URL matches exactly</td><td><code>-&gt;exact()</code></td><td>-</td></tr><tr><td>badge</td><td>Display a badge next to the title</td><td><code>-&gt;badge(&#39;green&#39;, &#39;10k+&#39;)</code> or <code>-&gt;badge(&#39;red&#39;)</code></td><td>string color: Tailwind color of the badge (red, green, blue, ...), string value: The value or null</td></tr><tr><td>addChildren</td><td>Add children to the item</td><td><code>-&gt;addChildren(new MenuItem(&#39;Child 1&#39;), new MenuItem(&#39;Child 2&#39;))</code></td><td>MenuItem item: One or many children</td></tr></tbody></table><h2 id="user-dropdown" tabindex="-1">User dropdown <a class="header-anchor" href="#user-dropdown" aria-hidden="true">#</a></h2><p>When you want to display menu items in the user dropdown simply use <code>Voyager\\Admin\\Classes\\UserMenuItem</code> instead of <code>Voyager\\Admin\\Classes\\MenuItem</code>.</p>`,9),p=[o];function c(i,d,u,l,r,k){return a(),s("div",null,p)}var h=n(e,[["render",c]]);export{g as __pageData,h as default};
