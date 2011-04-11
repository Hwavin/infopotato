<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; Using Library
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Using Library</h1>	

<p>InfoPotato lets you cache your pages in order to achieve maximum performance.</p> 
 
<p>Although InfoPotato is quite fast, the amount of dynamic information you display in your pages will correlate directly to the
server resources, memory, and processing cycles utilized, which affect your page load speeds.
By caching your pages, since they are saved in their fully rendered state, you can achieve performance that nears that of static web pages.</p> 
 
 
<h2>How Does Caching Work?</h2> 
 
<p>Caching can be enabled on a per-page basis, and you can set the length of time that a page should remain cached before being refreshed.
When a page is loaded for the first time, the cache file will be written to your <dfn>user-defined cache</dfn> folder.  On subsequent page loads the cache file will be retrieved
and sent to the requesting user's browser.  If it has expired, it will be deleted and refreshed before being sent to the browser.</p> 
 
<p>Note: The Benchmark tag is not cached so you can still view your page load speed when caching is enabled.</p> 
 
<h2>Define Cache Folder</h2> 
 
<p>To define the target cache folder, put the following code in <dfn>index.php</dfn></p> 
 
<div class="syntax">
<pre>
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_CACHE_DIR&#39;</span><span class="p">,</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;cache&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
</pre>
</div> 
 
<h2>Use Cache in Controller</h2> 
 
<div class="syntax">
<pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Controller</span> <span class="k">extends</span> <span class="nx">Controller</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;cache/cache_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cache&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;cache_dir&#39;</span><span class="o">=&gt;</span><span class="nx">APP_CACHE_DIR</span><span class="p">));</span> 
	<span class="nv">$cached_data</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cache</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;home&#39;</span><span class="p">);</span> 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$cached_data</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span>  
	    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Home&#39;</span><span class="p">,</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">fetch</span><span class="p">(</span><span class="s1">&#39;pages/home_content_view&#39;</span><span class="p">),</span> 
	    <span class="p">);</span> 
			
	    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">fetch</span><span class="p">(</span><span class="s1">&#39;layouts/layout_view&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="p">);</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
			
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">cache</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;home&#39;</span><span class="p">,</span> <span class="nv">$response_data</span><span class="p">[</span><span class="s1">&#39;content&#39;</span><span class="p">]);</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$cached_data</span><span class="p">,</span> 
	    <span class="p">);</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
	<span class="p">}</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre>
</div> 

</div> 
<!-- end onecolumn -->
