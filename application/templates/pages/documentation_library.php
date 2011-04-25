<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Using Library
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Using Library</h1>	

<p>The InfoPotato libraries are standalone and reusable PHP classes.  All of the available system libraries are located in your <dfn>system/libraries</dfn> folder.
In most cases, to use one of these classes involves initializing it within a controller using the following initialization function:</p> 

<p>
User defined application library must be placed at APP_LIBRARY_DIR
</p>

<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;library_folder/class_name&#39;</span><span class="p">,</span> <span class="s1">&#39;alias&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre>
</div> 
 
<p>Where <var>class name</var> is the name of the class you want to invoke.  For example, to load the validation class you would do this:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;email/email_library&#39;</span><span class="p">,</span> <span class="s1">&#39;em&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre>
</div>  
 
<p>Once initialized you can use it as indicated in the user guide page corresponding to that class.</p> 

<h2>Creating Your Own Libraries</h2> 
 
<p>Please read the section of the user guide that discusses how to create your own libraries.</p> 
 
 
</div> 
<!-- end onecolumn -->
