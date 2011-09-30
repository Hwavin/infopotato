<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Bottom to Top Stack Optimization with InfoPotato</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/">Tutorials</a> &gt; Bottom to Top Stack Optimization with InfoPotato
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/tutorial/optimization/'); ?>" class="print">Print</a>

<!-- PRINT: start -->

<p>
Speed is a big deal, and it's also a major reason that developers pick InfoPotato. It has a light footprint that consistently places it at the top of the PHP framework benchmarks. But keeping an application fast goes far beyond selecting a quick framework.
</p>

<p>
Performance of Web applications is affected by many factors. Database access, file system operations, network bandwidth are all potential affecting factors. InfoPotato has tried in every aspect to reduce the performance impact caused by the framework. But still, there are many places in the user application that can be improved to boost performance.
</p>

<h2>Enabling APC Extension</h2>

<p>
Enabling the <a href="http://www.php.net/manual/en/book.apc.php" class="external_link">PHP APC extension</a> is perhaps the easiest way to improve the overall performance of an application. The extension caches and optimizes PHP intermediate code and avoids the time spent in parsing PHP scripts for every incoming request. There is an informative and easy to follow tutorial to help you better <a href="http://techportal.ibuildings.com/2010/10/07/understanding-apc/" class="external_link">Understanding APC</a>.
</p>

<h2>Enabling System Runtime Cache</h2>

<p>
Here we call it runtime cache, the required core components are created with all the source code whitespace and comments stripped.
</p>

<p>
You can specify the runtime directory for the core components:
</p>

<div class="syntax"><pre>
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;SYS_RUNTIME_DIR&#39;</span><span class="p">,</span> <span class="nx">SYS_DIR</span><span class="o">.</span><span class="s1">&#39;runtime&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
</pre></div>

<p>
You can also control if you want to enable the core runtime cache on the application basis.
</p>

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * If cache the system core components to runtime files</span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;SYS_RUNTIME_CACHE&#39;</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span> 
</pre></div>

<h2>Database Optimization</h2>

<p>
Fetching data from database is often the main performance bottleneck in a Web application. Although using caching may alleviate the performance hit, it does not fully solve the problem. When the database contains enormous data and the cached data is invalid, fetching the latest data could be prohibitively expensive without proper database and query design.
</p>

<p>
Design index wisely in a database. Indexing can make SELECT queries much faster, but it may slow down INSERT, UPDATE or DELETE queries.
</p>

<p>
For complex queries, it is recommended to create a database view for it instead of issuing the queries inside the PHP code and asking DBMS to parse them repetitively.
</p>

<p>
Last but not least, use LIMIT in your SELECT queries. This avoids fetching overwhelming data from database and exhausting the memory allocated to PHP.
</p>

<h2>Combines Multiple CSS or JavaScript Files into A Single Download</h2>

<p>
Complex pages often need to include many external JavaScript and CSS files. Because each file would cause one extra round trip to the server and back, we should minimize the number of script files by merging them into fewer ones. We should also consider reducing the size of each script file to reduce the network transmission time. There are many tools around to help on these two aspects.
</p>

<div class="syntax"><pre>
<span class="k">final</span> <span class="k">class</span> <span class="nc">CSS_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span>
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">(</span><span class="k">array</span> <span class="nv">$params</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span>
	<span class="c1">// $css_files is an array created from $params[0]</span>
	<span class="nv">$css_files</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$params</span><span class="p">)</span> <span class="o">&gt;</span> <span class="m">0</span> <span class="o">?</span> <span class="nb">explode</span><span class="p">(</span><span class="s1">&#39;:&#39;</span><span class="p">,</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">])</span> <span class="o">:</span> <span class="k">NULL</span><span class="p">;</span>
		
	<span class="k">if</span> <span class="p">(</span><span class="nv">$css_files</span> <span class="o">!==</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span>
	    <span class="nv">$css_content</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">;</span>
	    <span class="k">foreach</span> <span class="p">(</span><span class="nv">$css_files</span> <span class="k">as</span> <span class="nv">$css_file</span><span class="p">)</span> <span class="p">{</span>
		<span class="nv">$css_content</span> <span class="o">.=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;css/&#39;</span><span class="o">.</span><span class="nv">$css_file</span><span class="p">);</span>
	    <span class="p">}</span>

	    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span>
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$css_content</span><span class="p">,</span>
		<span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/css&#39;</span>
	    <span class="p">);</span>
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span>
	<span class="p">}</span>
    <span class="p">}</span>
<span class="p">}</span>
</pre></div>

<p>
Then in the layout template, we can combine the CSS files in one request.
</p>

<div class="syntax"><pre><span class="c">&lt;!-- CSS Style --&gt;</span> 
<span class="nt">&lt;link</span> <span class="na">type=</span><span class="s">&quot;text/css&quot;</span> <span class="na">rel=</span><span class="s">&quot;stylesheet&quot;</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">css/index/main.css:fb-buttons.css&quot;</span> <span class="na">media=</span><span class="s">&quot;all&quot;</span> <span class="na">charset=</span><span class="s">&quot;utf-8&quot;</span> <span class="nt">/&gt;</span> 
</pre></div>

<div class="notebox">
JavaScript files can be processed as the same way.
</div>

<h2>Minify HTML</h2>

<p>
Minification is the practice of removing unnecessary characters from code to reduce its size thereby improving load times. When code is minified all comments are removed, as well as unneeded white space characters (space, newline, and tab).
</p>

<p>
InfoPotato provides a <a href="<?php echo APP_URI_BASE; ?>documentation//function/minify_html/">function to minify HTML content</a>.
</p>

<!-- PRINT: stop -->

</div> 

