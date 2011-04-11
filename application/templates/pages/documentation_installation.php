<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; Installation
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Installation</h1>	

<p>
Installation of InfoPotato mainly involves the following steps:
</p> 

<h2>Step 1</h2>
<p>
Download the lastest InfoPotato framework.
</p>

<h2>Step 2</h2>
<p>
Unpack the InfoPotato release file to a Web-accessible directory.
</p>

<h2>Step 3</h2>
<p>
Open the index.php file with a text editor and set your timezone, base URI, and default controller/action, and other application related directories.
</p>

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Set default time zone used by all date/time functions</span> 
<span class="sd"> */</span> 
<span class="nx">date_default_timezone_set</span><span class="p">(</span><span class="s1">&#39;America/New_York&#39;</span><span class="p">);</span> 
 
<span class="sd">/**</span> 
<span class="sd"> * Set public accessible web root, ending with the trailing slash &#39;/&#39;</span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_ENTRY_URI&#39;</span><span class="p">,</span> <span class="s1">&#39;http://localhost/mvc/index.php/&#39;</span><span class="p">);</span> 
</pre></div> 

<h2>General Config</h2>
<p>
If you intend to use a routing, open the application/configs/general.php file with a text editor and set your uri remap settings.
</p>

<h2>Database Config</h2>
<p>
If you intend to use a database, open the application/configs/database.php file with a text editor and set your database settings.
</p>

<h2>Increase Security</h2>
<p>
If you wish to increase security by hiding the location of your InfoPotato files you can rename the system and application folders to something more private. If you do rename them, you must open your main index.php file and set the SYS_DIR and APP_DIR variables at the top of the file with the new name you've chosen.
</p>

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Framework system/application directories</span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;SYS_DIR&#39;</span><span class="p">,</span> <span class="nb">dirname</span><span class="p">(</span><span class="k">__FILE__</span><span class="p">)</span><span class="o">.</span><span class="nx">DS</span><span class="o">.</span><span class="s1">&#39;system&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_DIR&#39;</span><span class="p">,</span> <span class="nb">dirname</span><span class="p">(</span><span class="k">__FILE__</span><span class="p">)</span><span class="o">.</span><span class="nx">DS</span><span class="o">.</span><span class="s1">&#39;application&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_VIEW_DIR&#39;</span><span class="p">,</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;views&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_MODEL_DIR&#39;</span><span class="p">,</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;models&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_CONFIG_DIR&#39;</span><span class="p">,</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;configs&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_CONTROLLER_DIR&#39;</span><span class="p">,</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;controllers&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="p">);</span> 
</pre></div>

<p class="tipbox">
InfoPotato does not need to be installed under a Web-accessible directory. An InfoPotato application has one entry script which is usually the only file that needs to be exposed to Web users. Other PHP scripts, including those from InfoPotato, should be protected from Web access since they may be exploited for hacking.
</p>

<h2>Troubleshooting</h2> 

<p>
f you find that no matter what you put in your URL only your default page is loading, it might be that your server does not support the PATH_INFO variable needed to serve search-engine friendly URLs.
</p>

</div> 
<!-- end onecolumn -->
