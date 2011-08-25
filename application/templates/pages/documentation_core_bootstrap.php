<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Bootstrap</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Request Processing Workflow
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/bootstrap/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Traditionally, applications were built so that each "page" of a site was its own physical file:
</p>

<div class="greenbox">
index.php<br />
contact.php<br />
blog.php
</div>

<p>
There are several problems with this approach, including the inflexibility of the URIs (what if you wanted to change blog.php to news.php without breaking all of your links?) and the fact that each file must manually include some set of core files so that security, database connections and the "look" of the site can remain consistent.
</p>

<p>
A much better solution is to use a single PHP file (it's called Front Controller sometimes) whose job is to bootstrap the InfoPotato application and handle every request coming into that application. For example:
</p>

<table class="grid"> 
<tbody> 
<tr><td><tt>/index.php</tt></td> 
<td>executes <tt>index.php</tt></td> 
</tr> 
<tr><td><tt>/index.php/contact</tt></td> 
<td>executes <tt>index.php</tt></td> 
</tr> 
<tr><td><tt>/index.php/blog</tt></td> 
<td>executes <tt>index.php</tt></td> 
</tr> 
</tbody> 
</table> 

<p>
Now, every request is handled exactly the same. Instead of individual URIs executing different PHP files, the front controller is always executed, and the routing of different URIs to different parts of your application is done internally. This solves both problems with the original approach. Almost all modern web apps do this - including apps like WordPress.
</p>

<p class="yellowbox">
Using Apache's <tt>mod_rewrite</tt> (or equivalent with other web servers), the URIs can easily be cleaned up to be just <tt>/</tt>, <tt>/contact</tt> and <tt>/blog</tt>.
</p> 

<h2>Hiding index.php</h2>

<p>
By default, InfoPotato urls contain index.php. To further clean our URIs, i.e., hiding the entry script index.php in the URI.
</p>

<p>
We first need to configure the Web server so that a URL without the entry script can still be handled by the entry script. If you are using Apache web server and wanted to have clean URLs without 'index.php', you would've to enable Mod_rewrite. We can create the file /wwwroot/blog/.htaccess with the following content. Note that the same content can also be put in the Apache configuration file within the Directory element for /wwwroot/blog.
</p>

<div class="greybox">
<pre>RewriteEngine on
 
# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
 
# otherwise forward it to index.php
RewriteRule . index.php
</pre>
</div>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
