<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Global Constants and Functions</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Global Constants and Functions
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/global/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>InfoPotato uses a few constants and functions for its operation that are globally defined, and are available to you at any point. These do not require loading any libraries or functions.</p> 
 
<h2>Global Constants</h2>

<p>
The following global constants are defined in the bootstrap (index.php or dev.php) script.
</p>

<table cellspacing="0" width="100%" class="grid"> 
<tr><td>ENVIRONMENT</td><td>Define the application environment (development/production)</td></tr>
<tr><td>INFOPOTATO_VERSION</td><td>Define InfoPotato Version</td></tr>
<tr><td>STATIC_URI_BASE</td><td>Used for static requests to access static assets (images) access, sometimes CDN is used</td></tr>
<tr><td>APP_URI_BASE</td><td>Set public accessible web root, ending with the trailing slash '/'</td></tr>
<tr><td>DS</td><td>Short name of DIRECTORY_SEPARATOR, '\' for Windows, '/' for Unix</td></tr>
<tr><td>SYS_DIR</td><td>Framework system directory</td></tr>
<tr><td>APP_DIR</td><td>Framework application directory</td></tr>
<tr><td>APP_DATA_DIR</td><td></td></tr>
<tr><td>APP_CONFIG_DIR</td><td></td></tr>
<tr><td>APP_MANAGER_DIR</td><td></td></tr>
<tr><td>APP_LIBRARY_DIR</td><td>User-defined application libraries</td></tr>
<tr><td>APP_TEMPLATE_DIR</td><td></td></tr>
<tr><td>DEFAULT_MANAGER</td><td>Default manager if none is given in the URL, case-sensetive </td></tr>
<tr><td>DEFAULT_MANAGER_METHOD</td><td>Default manager method if none is given in the URL, case-sensetive </td></tr>
</table>
 
<h2>Global Functions</h2>

<p>
Global_Functions is a class which contains two static functions can be used accross all the compontents of InfoPotato.
</p>

<h3>halt(<var>$heading</var>, <var>$message</var>, <var>$template = 'sys_error'</var>)</h3> 
 
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span><span class="nx">condition</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nx">halt</span><span class="p">(</span><span class="s1">&#39;An Error Was Encountered&#39;</span><span class="p">,</span> <span class="s1">&#39;Error message goes here&#39;</span><span class="p">,</span> <span class="s1">&#39;sys_error&#39;</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div> 
 
<h3>dump(<var>$variable</var>)</h3> 

<p>
For more information, please read <a href="<?php echo APP_URI_BASE; ?>documentation/core/dump/">Dump Variable</a> section.
</p>

<h3>__(<var>$string</var>)</h3> 

<p>
For more information, please read <a href="<?php echo APP_URI_BASE; ?>documentation/core/i18n_l10n/">Internationalization &amp; Localization</a> section.
</p>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>