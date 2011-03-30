<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; <a href="<?php echo BASE_URI; ?>documentation/">Documentation</a> &gt; Global Constants and Functions
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Global Constants and Functions</h1>	

<p>InfoPotato uses a few functions for its operation that are globally defined, and are available to you at any point. These do not require loading any libraries or helpers.</p> 
 
<h2>show_sys_error(<var>$heading</var>, <var>$message</var>, <var>$template = 'error_general'</var>)</h2> 
 
<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * General Error Page</span> 
<span class="sd"> *</span> 
<span class="sd"> * This function takes an error message as input (either as a string or an array)</span> 
<span class="sd"> * and displays it using the specified template.</span> 
<span class="sd"> * </span> 
<span class="sd"> * @param	string	the heading</span> 
<span class="sd"> * @param	string	the message</span> 
<span class="sd"> * @param	string	the template name</span> 
<span class="sd"> * @return	string</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">show_sys_error</span><span class="p">(</span><span class="nv">$heading</span><span class="p">,</span> <span class="nv">$message</span><span class="p">,</span> <span class="nv">$template</span> <span class="o">=</span> <span class="s1">&#39;error_general&#39;</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$message</span> <span class="o">=</span> <span class="s1">&#39;&lt;p&gt;&#39;</span><span class="o">.</span><span class="nb">implode</span><span class="p">(</span><span class="s1">&#39;&lt;/p&gt;&lt;p&gt;&#39;</span><span class="p">,</span> <span class="p">(</span> <span class="o">!</span> <span class="nb">is_array</span><span class="p">(</span><span class="nv">$message</span><span class="p">))</span> <span class="o">?</span> <span class="k">array</span><span class="p">(</span><span class="nv">$message</span><span class="p">)</span> <span class="o">:</span> <span class="nv">$message</span><span class="p">)</span><span class="o">.</span><span class="s1">&#39;&lt;/p&gt;&#39;</span><span class="p">;</span> 
	
    <span class="nv">$ob_level</span> <span class="o">=</span> <span class="nx">Bootstrap</span><span class="o">::</span><span class="na">instance</span><span class="p">()</span><span class="o">-&gt;</span><span class="na">ob_level</span><span class="p">;</span> 
	
    <span class="k">if</span> <span class="p">(</span><span class="nb">ob_get_level</span><span class="p">()</span> <span class="o">&gt;</span> <span class="nv">$ob_level</span> <span class="o">+</span> <span class="m">1</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nb">ob_end_flush</span><span class="p">();</span>	
    <span class="p">}</span> 
    <span class="nb">ob_start</span><span class="p">();</span> 
    <span class="k">include</span><span class="p">(</span><span class="nx">SYS_DIR</span><span class="o">.</span><span class="s1">&#39;error_tamplates/&#39;</span><span class="o">.</span><span class="nv">$template</span><span class="o">.</span><span class="s1">&#39;.php&#39;</span><span class="p">);</span> 
    <span class="nv">$buffer</span> <span class="o">=</span> <span class="nb">ob_get_contents</span><span class="p">();</span> 
    <span class="nb">ob_end_clean</span><span class="p">();</span> 
    <span class="k">echo</span> <span class="nv">$buffer</span><span class="p">;</span> 
    <span class="k">exit</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 
 
<h2>dump(<var>$variable</var>)</h2> 
 
<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Dump variable for simple debug</span> 
<span class="sd"> *</span> 
<span class="sd"> * Displays information about a variable in a human readable way</span> 
<span class="sd"> * </span> 
<span class="sd"> * @param	mixed the variable to be dumped</span> 
<span class="sd"> * @return	void</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">dump</span><span class="p">(</span><span class="nv">$var</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$content</span> <span class="o">=</span> <span class="s2">&quot;&lt;pre&gt;</span><span class="se">\n</span><span class="s2">&quot;</span><span class="p">;</span> 
    <span class="nv">$content</span> <span class="o">.=</span> <span class="nb">htmlspecialchars</span><span class="p">(</span><span class="nb">print_r</span><span class="p">(</span><span class="nv">$var</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">));</span> 
    <span class="nv">$content</span> <span class="o">.=</span> <span class="s2">&quot;</span><span class="se">\n</span><span class="s2">&lt;/pre&gt;</span><span class="se">\n</span><span class="s2">&quot;</span><span class="p">;</span> 
    <span class="k">echo</span> <span class="nv">$content</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div>  
 
 
<h2>load_script(<var>$script_filename</var>)</h2> 
 
<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Script Functions Loader</span> 
<span class="sd"> *</span> 
<span class="sd"> * If script is located in a sub-folder, include the relative path from scripts folder.</span> 
<span class="sd"> * Loaded script can be used by Model, View, and Controller</span> 
<span class="sd"> *</span> 
<span class="sd"> * @access	public</span> 
<span class="sd"> * @param   string $script the script plugin name</span> 
<span class="sd"> * @return  void</span> 
<span class="sd"> */</span>    
<span class="k">function</span> <span class="nf">load_script</span><span class="p">(</span><span class="nv">$script</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$script</span> <span class="o">=</span> <span class="nb">strtolower</span><span class="p">(</span><span class="nv">$script</span><span class="p">);</span> 
	
    <span class="c1">// Is the script in a sub-folder? If so, parse out the filename and path.</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nb">strpos</span><span class="p">(</span><span class="nv">$script</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">)</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$path</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	<span class="nv">$x</span> <span class="o">=</span> <span class="nb">explode</span><span class="p">(</span><span class="s1">&#39;/&#39;</span><span class="p">,</span> <span class="nv">$script</span><span class="p">);</span> 
	<span class="nv">$scrip</span> <span class="o">=</span> <span class="nb">end</span><span class="p">(</span><span class="nv">$x</span><span class="p">);</span>			
	<span class="nb">unset</span><span class="p">(</span><span class="nv">$x</span><span class="p">[</span><span class="nb">count</span><span class="p">(</span><span class="nv">$x</span><span class="p">)</span><span class="o">-</span><span class="m">1</span><span class="p">]);</span> 
	<span class="nv">$path</span> <span class="o">=</span> <span class="nb">implode</span><span class="p">(</span><span class="nx">DS</span><span class="p">,</span> <span class="nv">$x</span><span class="p">)</span><span class="o">.</span><span class="nx">DS</span><span class="p">;</span> 
    <span class="p">}</span> 
	
    <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nb">preg_match</span><span class="p">(</span><span class="s1">&#39;!^[a-z][a-z_]+$!&#39;</span><span class="p">,</span> <span class="nv">$script</span><span class="p">))</span> <span class="p">{</span> 
	<span class="nx">show_sys_error</span><span class="p">(</span><span class="s1">&#39;A System Error Was Encountered&#39;</span><span class="p">,</span> <span class="s2">&quot;Invalid script name &#39;</span><span class="si">{</span><span class="nv">$script</span><span class="si">}</span><span class="s2">&#39;&quot;</span><span class="p">,</span> <span class="s1">&#39;error_general&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
    <span class="c1">// Currently, all script functions are placed in system/scripts folder</span> 
    <span class="nv">$file_path</span> <span class="o">=</span> <span class="nx">SYS_DIR</span><span class="o">.</span><span class="s1">&#39;scripts&#39;</span><span class="o">.</span><span class="nx">DS</span><span class="o">.</span><span class="nv">$path</span><span class="o">.</span><span class="nv">$script</span><span class="o">.</span><span class="s1">&#39;.php&#39;</span><span class="p">;</span> 
	
    <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nb">file_exists</span><span class="p">(</span><span class="nv">$file_path</span><span class="p">))</span> <span class="p">{</span> 
	<span class="nx">show_sys_error</span><span class="p">(</span><span class="s1">&#39;An Error Was Encountered&#39;</span><span class="p">,</span> <span class="s2">&quot;Unknown script file &#39;</span><span class="si">{</span><span class="nv">$script</span><span class="si">}</span><span class="s2">&#39;&quot;</span><span class="p">,</span> <span class="s1">&#39;error_general&#39;</span><span class="p">);</span>		
    <span class="p">}</span> 
    <span class="k">return</span> <span class="k">require_once</span><span class="p">(</span><span class="nv">$file_path</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div> 

</div> 
<!-- end onecolumn -->
