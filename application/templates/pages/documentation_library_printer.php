<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Printer</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Printer
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/printer/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
This library helps you create printer friendly versions of your pages. All you need to do is to insert some tags in your pages, tags that will tell the script what needs to be printed from that specific page. An unlimited number of areas can be set for printing allowing you a flexible way of setting up the content to be printed. Besides, it can be instructed to transform links to a readable format or to remove img tags and replace them with a string.
</p>

<h2>Create a Print Manager</h2>
<div class="syntax"><pre>
<span class="k">final</span> <span class="k">class</span> <span class="nc">Print_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;printer/printer_library&#39;</span><span class="p">,</span> <span class="s1">&#39;p&#39;</span><span class="p">);</span> 
 
	<span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Printable Version&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">p</span><span class="o">-&gt;</span><span class="na">render</span><span class="p">(),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/print_layout&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h2>Define printable blocks using tags in template</h2>
<div class="syntax"><pre><span class="c">&lt;!-- PRINT: start --&gt;</span> 
content to print
<span class="c">&lt;!-- PRINT: stop --&gt;</span> 
 
other content
 
<span class="c">&lt;!-- PRINT: start --&gt;</span> 
another content to print
<span class="c">&lt;!-- PRINT: stop --&gt;</span> 
</pre></div> 

<div class="notebox">
An unlimited number of areas to be printed can be delimited as long as they are not contained inside another defined area!
</div>

<h2>Add a Print link button</h2>
<div class="syntax"><pre><span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">print&quot;</span> <span class="na">class=</span><span class="s">&quot;print&quot;</span><span class="nt">&gt;</span>Print<span class="nt">&lt;/a&gt;</span> 
</pre></div> 

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
