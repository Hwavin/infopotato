<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">User Agent Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; User Agent Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/user_agent/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>The User Agent Class provides functions that help identify information about the browser, mobile device, or robot visiting your site.
In addition you can get referrer information as well as language and supported character-set information.</p> 
 
<h2>Initializing the Class</h2> 
 
<p>Like most other libraries, the User Agent class is initialized in your controller using the <dfn>$this->load->library</dfn> function:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;user_agent/user_agent_library&#39;</span><span class="p">,</span> <span class="s1">&#39;ua&#39;</span><span class="p">);</span> 
</pre></div>

<p>Once loaded, the object will be available using: <dfn>$this->ua</dfn></p> 

<h2>Example</h2> 
 
<p>When the User Agent class is initialized it will attempt to determine whether the user agent browsing your site is
a web browser, a mobile device, or a robot.  It will also gather the platform information if it is available.</p> 
 
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">is_browser</span><span class="p">())</span> <span class="p">{</span>
    <span class="nv">$agent</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">browser</span><span class="p">()</span><span class="o">.</span><span class="s1">&#39; &#39;</span><span class="o">.</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">version</span><span class="p">();</span>
<span class="p">}</span> <span class="k">elseif</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">is_robot</span><span class="p">())</span> <span class="p">{</span>
    <span class="nv">$agent</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">robot</span><span class="p">();</span>
<span class="p">}</span> <span class="k">elseif</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">is_mobile</span><span class="p">())</span> <span class="p">{</span>
    <span class="nv">$agent</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ua</span><span class="o">-&gt;</span><span class="na">mobile</span><span class="p">();</span>
<span class="p">}</span> <span class="k">else</span> <span class="p">{</span>
    <span class="nv">$agent</span> <span class="o">=</span> <span class="s1">&#39;Unidentified User Agent&#39;</span><span class="p">;</span>
<span class="p">}</span>
</pre></div>
 
<h1>Function Reference</h1> 
 
<table width="100%" class="grid">
<thead>
<tr><th>Method</th><th>Description</th></tr>
</thead>

<tbody>
<tr><td>is_browser()</td><td>Returns TRUE/FALSE (boolean) if the user agent is a known web browser.</td></tr>
<tr><td>is_mobile()</td><td>Returns TRUE/FALSE (boolean) if the user agent is a known mobile device.</td></tr>
<tr><td>is_robot()</td><td>Returns TRUE/FALSE (boolean) if the user agent is a known robot.</td></tr>
<tr><td>is_referral()</td><td>Returns TRUE/FALSE (boolean) if the user agent was referred from another site.</td></tr>
<tr><td>browser()</td><td>Returns a string containing the name of the web browser viewing your site.</td></tr>
<tr><td>version()</td><td>Returns a string containing the version number of the web browser viewing your site.</td></tr>
<tr><td>mobile()</td><td>Returns a string containing the name of the mobile device viewing your site.</td></tr>
<tr><td>robot()</td><td>Returns a string containing the name of the robot viewing your site.</td></tr>
<tr><td>platform()</td><td>Returns a string containing the platform viewing your site (Linux, Windows, OS X, etc.).</td></tr>
<tr><td>referrer()</td><td>The referrer, if the user agent was referred from another site.</td></tr>
<tr><td>agent_string()</td><td>Returns a string containing the full user agent string.</td></tr>
<tr><td>accept_lang()</td><td>Lets you determine if the user agent accepts a particular language. 
<p class="notebox">
<strong>Note:</strong> This function is not typically very reliable
since some browsers do not provide language info, and even among those that do, it is not always accurate. 
</p> </td></tr>
<tr><td>accept_charset()</td><td>Lets you determine if the user agent accepts a particular character set. 
<p class="notebox">
<strong>Note:</strong> This function is not typically very reliable
since some browsers do not provide character-set info, and even among those that do, it is not always accurate. 
</p></td></tr>
</tbody>
</table> 

 
<!-- PRINT: stop --> 

<?php echo isset($pager) ? $pager : ''; ?>