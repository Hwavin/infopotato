<div class="container"> 

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Redirect Function</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/function/">Function Reference</a> &gt; Redirect Function
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/function/redirect/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
You can use a simple function to redirect a user from the page they entered to a different web page.
</p>

<h2>Loading this function in manager</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;redirect/redirect_function&#39;</span><span class="p">);</span> 
</pre></div> 

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Redirect</span> 
<span class="sd"> *</span> 
<span class="sd"> * @param string $url the url to be redirected</span> 
<span class="sd"> * @param integer $delay how many seconds to be delayed</span> 
<span class="sd"> * @param boolean $js whether to return JavaScript code for redirection</span> 
<span class="sd"> * @param boolean $js_wrapped whether to use &lt;script&gt; tag when returing JavaScript</span> 
<span class="sd"> * @param boolean $return whether to return JavaScript code</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">redirect_function</span><span class="p">(</span><span class="nv">$url</span><span class="p">,</span> <span class="nv">$delay</span> <span class="o">=</span> <span class="m">0</span><span class="p">,</span> <span class="nv">$js</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">,</span> <span class="nv">$js_wrapped</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">,</span> <span class="nv">$return</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// detail</span> 
<span class="p">}</span> 
</pre></div> 

<div class="syntax"><pre>
<span class="nx">redirect_function</span><span class="p">(</span><span class="nx">APP_URI_BASE</span><span class="o">.</span><span class="s1">&#39;about/founder/&#39;</span><span class="p">);</span> 
</pre></div> 

<div class="notebox">
You can edit the function and add more MIME types based on your actual needs.
</div>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
