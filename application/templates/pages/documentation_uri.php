<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; <a href="<?php echo BASE_URI; ?>documentation/">Documentation</a> &gt; URI
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">URI</h1>	

<p>
URIs in InfoPotato are composed of segments. A typical segmented URI ffollows this pattern:
</p>

<div class="greybox">
http://localhost/mvc/index.php/<span class="red">worker</span>/<span class="green">param1</span>/<span class="green">param2</span>
</div>

<p>
The segments correspond (in order ) to a controller, a controller method, and the method arguments.
</p>

<p>
Typically there is a one-to-one relationship between a URL string and its corresponding controller class/method. The segments in a URI normally follow this pattern:
</p> 
 
<code>example.com/<dfn>class</dfn>/<samp>function</samp>/<var>id</var>/</code> 
 
<p>
In some instances, however, you may want to remap this relationship so that a different class/function can be called instead of the one corresponding to the URI.
</p> 
 
<p>
For example, lets say you want your URLs to have this prototype:
</p> 
 
<p> 
example.com/product/1/<br /> 
example.com/product/2/<br /> 
example.com/product/3/<br /> 
example.com/product/4/
</p> 
 
<p>
Normally the second segment of the URL is reserved for the function name, but in the example above it instead has a product ID. To overcome this, InfoPotato allows you to remap the URI handler.
</p> 
 
 
<h2>Setting your own routing rules</h2> 
 
<p>
Routing rules are defined in your application/config/routes.php file.  In it you'll see an array called $route that permits you to specify your own routing criteria. Routes can either be specified using wildcards or Regular Expressions
</p> 
 
 
<h2>Wildcards</h2> 
 
<p>
A typical wildcard route might look something like this:
</p> 
 
<code>$route['product/:num'] = "catalog/product_lookup";</code> 
 
<p>
In a route, the array key contains the URI to be matched, while the array value  contains the destination it should be re-routed to. In the above example, if the literal word "product" is found in the first segment of the URL, and a number is found in the second segment, the "catalog" class and the "product_lookup" method are instead used.
</p> 
 
<p>
You can match literal values or you can use two wildcard types:
</p> 
 
<p><strong>(:num)</strong> will match a segment containing only numbers.<br /> 
<strong>(:any)</strong> will match a segment containing any character.
</p> 
 
<p class="important"><strong>Note:</strong> Routes will run in the order they are defined.
Higher routes will always take precedence over lower ones.</p> 
 
<h2>Examples</h2> 
 
<p>Here are a few routing examples:</p> 
 
<code>$route['journals'] = "blogs";</code> 
<p>A URL containing the word "journals" in the first segment will be remapped to the "blogs" class.</p> 
 
<code>$route['blog/joe'] = "blogs/users/34";</code> 
<p>A URL containing the segments blog/joe will be remapped to the "blogs" class and the "users" method.  The ID will be set to "34".</p> 
 
<code>$route['product/(:any)'] = "catalog/product_lookup";</code> 
<p>A URL with "product" as the first segment, and anything in the second will be remapped to the "catalog" class and the  "product_lookup" method.</p> 
 
<code>$route['product/(:num)'] = "catalog/product_lookup_by_id/$1";</code> 
<p>A URL with "product" as the first segment, and a number in the second will be remapped to the "catalog" class and the "product_lookup_by_id" method passing in the match as a variable to the function.</p> 
 
<p class="important"><strong>Important:</strong> Do not use leading/trailing slashes.</p> 
 
<h2>Regular Expressions</h2> 
 
<p>If you prefer you can use regular expressions to define your routing rules.  Any valid regular expression is allowed, as are back-references.</p> 
 
<p class="important"><strong>Note:</strong>&nbsp; If you use back-references you must use the dollar syntax rather than the double backslash syntax.</p> 
 
<p>A typical RegEx route might look something like this:</p> 
 
<code>$route['products/([a-z]+)/(\d+)'] = "$1/id_$2";</code> 
 
<p>In the above example, a URI similar to <dfn>products/shirts/123</dfn> would instead call the <dfn>shirts</dfn> controller class and the <dfn>id_123</dfn> function.</p> 
 
<p>You can also mix and match wildcards with regular expressions.</p> 
 
<h2>Reserved Routes</h2> 
 
<p>There are two reserved routes:</p> 
 
<code>$route['default_controller'] = 'welcome';</code> 
 
<p>This route indicates which controller class should be loaded if the URI contains no data, which will be the case
when people load your root URL. In the above example, the "welcome" class would be loaded.  You
are encouraged to always have a default route otherwise a 404 page will appear by default.</p> 
 
<p class="important"><strong>Important:</strong>&nbsp; The reserved routes must come before any wildcard or regular expression routes.</p> 

<h2>Hiding index.php</h2>

<p>
By default, InfoPotato urls contain index.php. To further clean our URLs, i.e., hiding the entry script index.php in the URL.
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

</div> 
<!-- end onecolumn -->
