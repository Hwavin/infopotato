<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Download Function
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Download Function</h1>	

<p>
The download function will force a file to be saved to your desktop.
</p>

<h2>Loading this function in manager</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;download/download_function&#39;</span><span class="p">);</span> 
</pre></div> 

<p>
Once this function is loaded, <strong>download_function($file, $mime_type = '')</strong> will be available to use.
</p>

<div class="syntax"><pre>
<span class="nx">download_function</span><span class="p">(</span><span class="nv">$download_dir</span><span class="o">.</span><span class="s1">&#39;ZhouYuan_Resume.doc&#39;</span><span class="p">);</span> 
</pre></div>

<div class="tipbox">
You can edit the function and add more MIME types based on your actual needs.
</div>

</div> 
<!-- end onecolumn -->
