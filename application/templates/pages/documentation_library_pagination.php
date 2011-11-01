<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Pagination Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Pagination Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/pagination/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Here is a simple example showing how to create pagination in one of your manager methods:
</p>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_pagination</span><span class="p">(</span><span class="nv">$params</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
    <span class="nv">$current_page</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$params</span><span class="p">)</span> <span class="o">&gt;</span> <span class="m">0</span> <span class="o">?</span> <span class="p">(</span><span class="nx">int</span><span class="p">)</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">]</span> <span class="o">:</span> <span class="m">1</span><span class="p">;</span> 
 
    <span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;base_uri&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;http://www.example.com/index.php/pagination/index/&#39;</span><span class="p">,</span> 
	<span class="s1">&#39;items_total&#39;</span> <span class="o">=&gt;</span> <span class="m">200</span><span class="p">,</span> 
        <span class="s1">&#39;items_per_page&#39;</span> <span class="o">=&gt;</span> <span class="m">10</span><span class="p">,</span> 
        <span class="s1">&#39;mid_range&#39;</span> <span class="o">=&gt;</span> <span class="m">9</span><span class="p">,</span> 
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

<h2>Get all the pagination data for simple debug</h2>

<p>
You can also get an array which contains all the pagination data by calling the <span class="red">get_pagination_data()</span> method.
</p>

<div class="syntax"><pre>
<span class="nx">Global_Functions</span><span class="o">::</span><span class="na">dump</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">get_pagination_data</span><span class="p">());</span> 
</pre></div>  

<h2>Sample Template and Output</h2>

<div class="syntax"><pre><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;pagination&quot;</span><span class="nt">&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$pagination</span><span class="p">;</span><span class="cp">?&gt;</span> 
<span class="nt">&lt;/div&gt;</span> 
</pre></div> 

<p>
After template renderring, you will get the following HTML output:
</p>

<div class="syntax"><pre><span class="nt">&lt;div</span> <span class="na">class=</span><span class="s">&quot;pagination&quot;</span><span class="nt">&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/9&quot;</span><span class="nt">&gt;</span><span class="ni">&amp;laquo;</span> Prev<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/1&quot;</span><span class="nt">&gt;</span>1<span class="nt">&lt;/a&gt;</span>...
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/7&quot;</span><span class="nt">&gt;</span>7<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">quot;http://www.example.com/index.php/pagination/index/8&quot;</span><span class="nt">&gt;</span>8<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/9&quot;</span><span class="nt">&gt;</span>9<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;span</span> <span class="na">class=</span><span class="s">&quot;current_page&quot;</span><span class="nt">&gt;</span>10<span class="nt">&lt;/span&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/11&quot;</span><span class="nt">&gt;</span>11<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/12&quot;</span><span class="nt">&gt;</span>12<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/13&quot;</span><span class="nt">&gt;</span>13<span class="nt">&lt;/a&gt;</span>...
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/20&quot;</span><span class="nt">&gt;</span>20<span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;http://www.example.com/index.php/pagination/index/11&quot;</span><span class="nt">&gt;</span>Next <span class="ni">&amp;raquo;</span><span class="nt">&lt;/a&gt;</span> 
<span class="nt">&lt;/div&gt;</span> 
</pre></div> 

<h2>Adding some CSS style</h2>

<div class="syntax"><pre>.pagination {
margin-bottom:10px;
}
 
.pagination a{
padding:2px 6px;
margin:0 5px;
border:3px solid #ddd;
olor:#fff;
}
 
.pagination a:hover {
background-color:#f7f7f7;
border:3px solid #3b5998;
text-decoration:underline;
color:#3b5998;
}
 
.pagination span.current_page {
border:3px solid #3b5998;
padding:2px 6px;
margin:0 3px;
color:#3b5998;
background-color:#fff;
text-decoration:none;
}
</pre></div>

<h2>Final Visual Output</h2>
<p>When the first page is the current page</p>
<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination.jpg" title="Pagination" />

<p>When the 10th page is the current page</p>
<img src="<?php echo STATIC_URI_BASE; ?>images/content/pagination2.jpg" title="Pagination" />
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>