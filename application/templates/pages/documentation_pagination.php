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
    <span class="nv">$current_page</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$params</span><span class="p">)</span> <span class="o">&gt;</span> <span class="m">0</span> <span class="o">?</span> <span class="p">(</span><span class="nx">int</span><span class="p">)</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">]</span> <span class="o">:</span> <span class="m">1</span><span class="p">;</span> 
 
    <span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;base_uri&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;http://www.example.com/index.php/pagination/index/&#39;</span><span class="p">,</span> 
	<span class="s1">&#39;items_total&#39;</span> <span class="o">=&gt;</span> <span class="m">200</span><span class="p">,</span> 
	<span class="s1">&#39;current_page&#39;</span> <span class="o">=&gt;</span> <span class="nv">$current_page</span><span class="p">,</span> 
	<span class="s1">&#39;current_page_class&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;current_page&#39;</span> 
    <span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;pagination/pagination_library&#39;</span><span class="p">,</span> <span class="s1">&#39;page&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
	
    <span class="nv">$pagination</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">build_pagination</span><span class="p">();</span>	
<span class="p">}</span> 
</pre></div>

<div class="notebox">
'current_page_class' is the user-defined CSS class slelector for the current page
</div>

<p>How it looks - Sample</p>

<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination.jpg" title="Pagination" />

<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination2.jpg" title="Pagination" />

</div> 
<!-- end onecolumn -->
