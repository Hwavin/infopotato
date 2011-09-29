<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">System Core Runtime Cache</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; System Core Runtime Cache
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/runtime/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
To speed up the overall perfermance, InfoPotato enables the developers to cache the system core components for further use. 
</p>

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
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

