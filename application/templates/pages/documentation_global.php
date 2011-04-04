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

<p>InfoPotato uses a few constants and functions for its operation that are globally defined, and are available to you at any point. These do not require loading any libraries or helpers.</p> 
 
<h2>Global Constants</h2>
 
<table>
<tr><td>ENVIRONMENT</td><td>Define the application environment</td></tr>
<tr><td>INFOPOTATO_VERSION</td><td>Define InfoPotato Version</td></tr>
<tr><td>BASE_URI</td><td>Set public accessible web root, ending with the trailing slash '/'</td></tr>
<tr><td>DS</td><td>Short name of DIRECTORY_SEPARATOR, '\' for Windows, '/' for Unix</td></tr>
<tr><td>SYS_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_DATA_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_CONFIG_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_WORKER_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_LIBRARY_DIR</td><td>Define the application environment</td></tr>
<tr><td>APP_TEMPLATE_DIR</td><td>Define the application environment</td></tr>
<tr><td>DEFAULT_WORKER</td><td>Default worker if none is given in the URL, case-sensetive </td></tr>
<tr><td>PERMITTED_URI_CHARS</td><td>Default allowed URL Characters (UTF-8 encoded characters)</td></tr>
</table>
 
<h2>Global Functions</h2>
<h3>show_sys_error(<var>$heading</var>, <var>$message</var>, <var>$template = 'sys_error'</var>)</h3> 
 
<h3>dump(<var>$variable</var>)</h3> 
 
<h3>load_script(<var>$script_filename</var>)</h3> 
 
</div> 
<!-- end onecolumn -->
