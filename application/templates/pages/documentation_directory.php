<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; Directory
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Directory</h1>	

<p>
This function reads the directory path specified in the first parameter and builds an array representation of it and all its contained files.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="nx">load_script</span><span class="p">(</span><span class="s1">&#39;directory_map/directory_map_script&#39;</span><span class="p">);</span> 
<span class="nv">$map</span> <span class="o">=</span> <span class="nx">directory_map</span><span class="p">(</span><span class="nx">APP_DIR</span><span class="p">);</span> 
<span class="nx">dump</span><span class="p">(</span><span class="nv">$map</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div>

<p>
Sub-folders contained within the directory will be mapped as well. If you wish to control the recursion depth, you can do so using the second parameter (integer). A depth of 1 will only map the top level directory:
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="nv">$map</span> <span class="o">=</span> <span class="nx">directory_map</span><span class="p">(</span><span class="s1">&#39;./mydirectory/&#39;</span><span class="p">,</span> <span class="m">1</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>
By default, hidden files will not be included in the returned array. To override this behavior, you may set a third parameter to TRUE (boolean):
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="nv">$map</span> <span class="o">=</span> <span class="nx">directory_map</span><span class="p">(</span><span class="s1">&#39;./mydirectory/&#39;</span><span class="p">,</span> <span class="k">FALSE</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>
Each folder name will be an array index, while its contained files will be numerically indexed. Here is an example of a typical array:
</p>

<div class="syntax"><pre>Array
(
   [libraries] =&gt; Array
   (
       [0] =&gt; benchmark.html
       [1] =&gt; config.html
       [database] =&gt; Array
       (
           [0] =&gt; active_record.html
           [1] =&gt; binds.html
           [2] =&gt; configuration.html
           [3] =&gt; connecting.html
           [4] =&gt; examples.html
           [5] =&gt; fields.html
           [6] =&gt; index.html
           [7] =&gt; queries.html
       )
       [2] =&gt; email.html
       [3] =&gt; file_uploading.html
       [4] =&gt; image_lib.html
       [5] =&gt; input.html
       [6] =&gt; language.html
       [7] =&gt; loader.html
       [8] =&gt; pagination.html
       [9] =&gt; uri.html
    )
)
</pre></div>

</div> 
<!-- end onecolumn -->
