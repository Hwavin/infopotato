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

<div class="quote_item">
<div class="quote_author">
<img src="<?php echo STATIC_URI_BASE; ?>images/authors/mark_twain.jpg" class="quote_author_photo round_corner_img" width="40" height="40" />
<span class="quote_author_name">Mark Twain<span>
</div>

<blockquote>
<div class="quote_content">
<span class="curly_quote_open"></span>
<span class="quote_text">Continuous Improvement is better than delayed perfection.</span>
<span class="curly_quote_close"></span>
</div>
</blockquote>
</div>

<p>
Performance of Web applications is affected by many factors. Database access, file system operations, network bandwidth are all potential affecting factors. InfoPotato has tried in every aspect to reduce the performance impact caused by the framework. But still, there are many places in the user application that can be improved to boost performance.
</p>

<h2>Enabling APC Extension</h2>

<p>
Enabling the <a href="http://www.php.net/manual/en/book.apc.php" class="external_link">PHP APC extension</a> is perhaps the easiest way to improve the overall performance of an application. The extension caches and optimizes PHP intermediate code and avoids the time spent in parsing PHP scripts for every incoming request. There is an informative and easy to follow tutorial to help you better <a href="http://techportal.ibuildings.com/2010/10/07/understanding-apc/" class="external_link">Understanding APC</a>.
</p>

<h2>Using Varnish Cache</h2>

<p>
<a href="https://www.varnish-cache.org/" class="external_link">Varnish</a> is an HTTP accelerator designed for content-heavy dynamic web sites. In contrast to other HTTP accelerators, such as Squid, which began life as a client-side cache, or Apache and nginx, which are primarily origin servers, Varnish was designed from the ground up as an HTTP accelerator. Varnish is focused exclusively on HTTP, unlike other proxy servers that often support FTP, SMTP and other network protocols.
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
InfoPotato provides a <a href="<?php echo APP_URI_BASE; ?>documentation/function/minify_html/">function to minify HTML content</a>.
</p>

<h2>Tips to Speed Up Your PHP Code</h2>

<p>
It&#8217;s time to break out of those lazy habits and start coding with performance in mind.  Practice these PHP performance tips and watch your code go from sluggish to speedy in no time.
</p>

<ul>
<li><strong>Use echo instead of print().</strong> As a language construct rather than a function, echo has a slight performance advantage over print().</li>
<li><strong>Echo with commas, not periods.</strong> I&#8217;m a repeat offender of this one.  If you use periods, PHP has to concatenate the string before it outputs.  If you use commas, it just outputs them in order with no extra processing.</li>
<li><strong>Avoid function tests in loop conditionals.</strong> If you&#8217;re looping through an array, for example, count() it beforehand, store the value in a variable, and use that for your test.  This way, you avoid needlessly firing the test function with every loop iteration.</li>
<li><strong>Use include() and require() instead of include_once() and require_once().</strong> There&#8217;s a lot of work involved in checking to see if a file has already been included.  Sometimes it&#8217;s necessary, but you should default to include() and require() in most situations.</li>
<li><strong>Use full file paths on include/require statements.</strong> Normalizing a relative file path can be expensive; giving PHP the absolute path (or even &#8220;./file.inc&#8221;) avoids the extra step.</li>
<li><strong>Favor built-in functions over custom functions.</strong> Since PHP has to take the extra step of interpreting your custom functions, built-in functions have a performance advantage.  More importantly, there are a lot of useful built-in functions that you may never learn about if you always default to writing your own.</li>
<li><strong>Avoid needlessly copying variables.</strong> If the variable is quite large, this could result in a lot of extra processing.  Use the copy you already whenever possible, even if it doesn&#8217;t look pretty (e.g., $_POST['somevariable']).</li>
<li><strong>Pass unchanged variables to a function by reference rather than value.</strong> This goes hand-in-hand with the point about needlessly copying variables.  Much of the time, your functions only need to use the values from their parameters without changing them.  In such cases, you can safely pass those parameters by reference (e.g., function(&amp;$parameter) rather than function($parameter)) and avoid having to make memory-intensive copies.</li>
<li><strong>Debug with error_reporting(E_ALL).</strong> Every warning is a performance improvement waiting to happen, but only if you can see it.  Cleaning up warnings and errors beforehand can also keep you from using @ error suppression, which is expensive.  Just don&#8217;t forget to turn off error reporting when you&#8217;re done; warnings and errors are expensive as well.</li>
<li><strong>Ditch double quotes for single quotes.</strong> There&#8217;s some disagreement, but the common wisdom is that PHP has to do extra processing on a string in double quotes to see if it contains any variables.  Concatenation with single quotes is marginally faster.</li>
</ul>

<h2>Enabling HTTP GZIP Compression</h2>

<p>
Compression is a simple, effective way to save bandwidth and speed up your application. There are two ways to compress output. One of them is apache's mod_gzip module and the other is php's output buffering, and the two methods are redundant to one another. Between the two, mod_gzip is generally easier to set up and more comprehensive&mdash;as it's part of Apache, mod_gzip can compress content (like static HTML, Javascript, and CSS) which isn't served through PHP. Here is a good article which <a href="http://betterexplained.com/articles/how-to-optimize-your-site-with-gzip-compression/" class="external_link">explains more about GZIP compression</a>.
</p>

<p>
If your hosting server doesn't support GZIP, you can use <a href="<?php echo APP_URI_BASE; ?>library/gzip_compress/">InfoPotato's gzip compress library</a>.
</p>

<h2>Code Profiling</h2>

<p>
Xdebug's built-in profiler allows you to find bottlenecks in your script and visualize those with an external tool such as KCacheGrind or WinCacheGrind. There is also a web based front-end called <a href="http://code.google.com/p/webgrind/" class="external_link">Webgrind</a>.
</p>

<!-- PRINT: stop -->