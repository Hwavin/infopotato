<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Mobile Device Detection</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Mobile Device Detection
</div>
<!-- end breadcrumb -->

<p>
Mobile_Detect is a simple PHP class for easy detection of the most popular mobile devices platforms: iPhone, iPad, Android, Blackberry, Opera Mini, Palm, Windows Mobile, as well as generic ones.
</p>

<h2>Use this library in manager</h2>

<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;mobile_detect/mobile_detect_library&#39;</span><span class="p">,</span> <span class="s1">&#39;md&#39;</span>);</span> 
</pre> 
</div> 

<p>Check for a specific platform:</p>

<div class="syntax"><pre>
<span class="c1">// is_android() | is_blackberry() | is_opera() | is_palm() | is_windows() | is_generic()</span> 
<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">md</span><span class="o">-&gt;</span><span class="na">is_android</span><span class="p">())</span> <span class="p">{</span> 
    <span class="c1">// code to run for the Google Android platform</span> 
<span class="p">}</span> 
</pre></div> 

<p>
Alternatively, if you are only interested in checking to see if the user is using a mobile device, without caring for specific platform:
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">md</span><span class="o">-&gt;</span><span class="na">is_mobile</span><span class="p">())</span> <span class="p">{</span> 
    <span class="c1">// any mobile platform</span> 
<span class="p">}</span> 
</pre></div>

<p>
Here is how to check to see if the user is a mobile but not an iPad:
</p>

<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">md</span><span class="o">-&gt;</span><span class="na">is_mobile</span><span class="p">()</span> <span class="o">&amp;&amp;</span> <span class="o">!</span> <span class="nv">$$this</span><span class="o">-&gt;</span><span class="na">md</span><span class="o">-&gt;</span><span class="na">is_ipad</span><span class="p">())</span> <span class="p">{</span> 
    <span class="c1">// any mobile platform that is not iPad</span> 
<span class="p">}</span> 
</pre></div> 

</div> 
<!-- end onecolumn -->
