<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Pagination Library</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Pagination Library
</div>
<!-- end breadcrumb -->

<p>
Here is a simple example showing how to create pagination in one of your manager methods:
</p>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_pagination</span><span class="p">(</span><span class="nv">$params</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
    <span class="nv">$current_page</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$params</span><span class="p">)</span> <span class="o">&gt;</span> <span class="m">0</span> <span class="o">?</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">]</span> <span class="o">:</span> <span class="m">1</span><span class="p">;</span> 
		
    <span class="c1">// Load Pagination library</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;pagination/pagination_library&#39;</span><span class="p">,</span> <span class="s1">&#39;page&#39;</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">items_per_page</span> <span class="o">=</span> <span class="m">30</span><span class="p">;</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">mid_range</span> <span class="o">=</span> <span class="m">7</span><span class="p">;</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">current_page</span> <span class="o">=</span> <span class="nv">$current_page</span><span class="p">;</span>	
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">items_total</span> <span class="o">=</span> <span class="m">300</span><span class="p">;</span>	
    
    <span class="nv">$pagination_data</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">build_pagination</span><span class="p">();</span>	
<span class="p">}</span> 
</pre></div> 

<p>Code in template</p>

<div class="syntax"><pre><span class="c">&lt;!-- begin pagination --&gt;</span> 
<span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;pagination&quot;</span><span class="nt">&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;current_page&#39;</span><span class="p">]</span> <span class="o">!=</span> <span class="m">1</span><span class="p">)</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">class=</span><span class="s">&quot;page&quot;</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">BASE_URI</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">admin/all/</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;prev_page&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">title=</span><span class="s">&quot;Previous Page&quot;</span><span class="nt">&gt;</span><span class="ni">&amp;laquo;</span> Prev<span class="nt">&lt;/a&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
 
<span class="cp">&lt;?php</span> <span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="m">1</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;=</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">];</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;range&#39;</span><span class="p">][</span><span class="m">0</span><span class="p">]</span> <span class="o">&gt;</span> <span class="m">2</span> <span class="o">&amp;&amp;</span> <span class="nv">$i</span> <span class="o">==</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;range&#39;</span><span class="p">][</span><span class="m">0</span><span class="p">])</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
...
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">==</span> <span class="m">1</span> <span class="o">||</span> <span class="nv">$i</span> <span class="o">==</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">]</span> <span class="o">||</span> <span class="nb">in_array</span><span class="p">(</span><span class="nv">$i</span><span class="p">,</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;range&#39;</span><span class="p">]))</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">==</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;current_page&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">title=</span><span class="s">&quot;Go to page </span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$i</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s"> of </span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">class=</span><span class="s">&quot;current_page&quot;</span> <span class="na">href=</span><span class="s">&quot;#&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$i</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="nt">&lt;/a&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">else</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">class=</span><span class="s">&quot;page&quot;</span> <span class="na">title=</span><span class="s">&quot;Go to page </span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$i</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s"> of </span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">BASE_URI</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">admin/all/</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$i</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$i</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="nt">&lt;/a&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;range&#39;</span><span class="p">][</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;mid_range&#39;</span><span class="p">]</span><span class="o">-</span><span class="m">1</span><span class="p">]</span> <span class="o">&lt;</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">]</span><span class="o">-</span><span class="m">1</span> <span class="o">&amp;&amp;</span> <span class="nv">$i</span> <span class="o">==</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;range&#39;</span><span class="p">][</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;mid_range&#39;</span><span class="p">]</span><span class="o">-</span><span class="m">1</span><span class="p">])</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
...
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endfor</span><span class="p">;</span> <span class="cp">?&gt;</span> 
 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;current_page&#39;</span><span class="p">]</span> <span class="o">!=</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">class=</span><span class="s">&quot;page&quot;</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">BASE_URI</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">admin/all/</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;next_page&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="na">title=</span><span class="s">&quot;Next Page&quot;</span><span class="nt">&gt;</span>Next <span class="ni">&amp;raquo;</span><span class="nt">&lt;/a&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
(Page <span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;current_page&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span> of <span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;num_pages&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span>, <span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$page_data</span><span class="p">[</span><span class="s1">&#39;items_per_page&#39;</span><span class="p">];</span> <span class="cp">?&gt;</span> requests per page)
<span class="nt">&lt;/div&gt;</span> 
<span class="c">&lt;!-- end pagination --&gt;</span> 
</pre></div> 

<p>How it looks</p>

<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination.jpg" title="Pagination" />

<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination2.jpg" title="Pagination" />

</div> 
<!-- end onecolumn -->
