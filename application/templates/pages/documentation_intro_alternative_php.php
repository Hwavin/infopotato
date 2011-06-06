<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Alternate PHP Syntax for View Files</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; Alternate PHP Syntax for View Files
</div>
<!-- end breadcrumb -->

<p>
PHP offers an alternative syntax for some of its control structures; namely, if, while, for, foreach, and switch. In each case, the basic form of the alternate syntax is to change the opening brace to a colon (:) and the closing brace to endif;, endwhile;, endfor;, endforeach;, or endswitch;, respectively.
</p>

<p>
You'll be using pure PHP in your View files when you need to mix PHP codes with HTML. To minimize the PHP code in these files, and to make it easier to identify the code blocks it is recommended that you use PHPs alternative syntax for control structures and short tag echo statements.  If you are not familiar with this syntax, it allows you to eliminate the braces from your code, and eliminate "echo" statements.
</p> 
 
<h2>Examples</h2> 

<h3>if</h3>
<div class="syntax">
<pre><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span><span class="o">:</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="x">    &lt;!--html code here--&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre>
</div>
 
<h3>if/else</h3>
<div class="syntax">
<pre><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span><span class="o">:</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="x">    &lt;!--html code here--&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">else</span> <span class="o">:</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="x">    &lt;!--html code here--&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre>
</div> 

<h3>foreach</h3>
<div class="syntax">
<pre><span class="cp">&lt;?php</span> <span class="k">foreach</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span><span class="o">:</span> <span class="cp">?&gt;</span><span class="x"></span> 
<span class="x">    &lt;!--html code here--&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endforeach</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="x"></span> 
</pre>
</div>

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
