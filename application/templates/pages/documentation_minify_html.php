<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Minify HTML Function
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Minify HTML Function</h1>	

<p>
Minification is the practice of removing unnecessary characters from code to reduce its size thereby improving load times.
</p>

<h2>Loading this function in manager</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;minify_html/minify_html_function&#39;</span><span class="p">);</span> 
</pre></div> 

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Minify html</span> 
<span class="sd"> *</span> 
<span class="sd"> * Based on PeecFW HTMLCompressor</span> 
<span class="sd"> *</span> 
<span class="sd"> * @param string $html HTML content to be minified</span> 
<span class="sd"> * </span> 
<span class="sd"> * @return minified HTML content</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">minify_html_function</span><span class="p">(</span><span class="nv">$html</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// detail</span> 
<span class="p">}</span> 
</pre></div> 

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Home_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;minify_html/minify_html_function&#39;</span><span class="p">);</span> 
        <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nx">minify_html_function</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;home&#39;</span><span class="p">)),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	<span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

</div> 
<!-- end onecolumn -->
