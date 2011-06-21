<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">htmlawed Function</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/function/">Function Reference</a> &gt; htmlawed Function
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/function/htmlawed/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
htmLawed makes input text more secure, HTML standards-compliant, and suitable in general from the viewpoint of a web-page administrator, for use in the body of HTML, XHTML or XML documents. A simple HTMLTidy alternative, the htmLawed filter, processor, purifier, sanitizer, beautifier, etc., is highly customizable.
</p>

<p>
It ensures that HTML tags are balanced and properly nested tags, neutralizes code that may be used for cross-site scripting (XSS) attacks, limits allowed HTML elements, attributes, or URL protocols, tidies the code, and so forth.
</p>

<h2>Loading this function in manager</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;minify_html/minify_html_function&#39;</span><span class="p">);</span> 
</pre></div> 


<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
