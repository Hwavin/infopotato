<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; User-defined Functions
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">User-defined Functions</h1>	

<p>
User-defined Functions, as the name suggests, help you with tasks. Each helper file is simply a collection of functions in a particular category.
</p> 

<p>
Unlike most other systems in InfoPotato, User-defined Functions are not written in an Object Oriented format. They are simple, procedural functions. Each helper function performs one specific task, with no dependence on other functions.
</p>

<p>
Helper is loaded using the global function load_script(), which can be used by Model, Controller, and View.
</p>
 
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="c1">// Load force download script</span> 
<span class="nx">load_script</span><span class="p">(</span><span class="s1">&#39;download/download_script&#39;</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div>

<p>
Then the functions in download_script.php are available to be called.
</p>

</div> 
<!-- end onecolumn -->
