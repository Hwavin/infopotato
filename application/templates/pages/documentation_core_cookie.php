<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Cookie</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Cookie
</div>
<!-- end breadcrumb -->

<p>
The Cookie class helps to make PHP's cookie API more consistent, while adding the ability to set default parameters and create httponly cookies in older versions of PHP 5.
</p>

<h2>Setting Values</h2>
<p>
The static method set() is a wrapper around the function setcookie(), with the addition of optional default parameters and backwards compatibility for creating httponly cookies in PHP 5.1 and earlier. In addition, the expires parameter has been extended to allow any valid strtotime() string or a timestamp. Below are some examples of using set():
</p>

<div class="syntax"><pre>
<span class="c1">// Set a cookie for the current path </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">);</span> 
 
<span class="c1">// Set a cookie to expire in 1 week </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="s1">&#39;+1 week&#39;</span><span class="p">);</span> 
 
<span class="c1">// Set a cookie for the whole site </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="s1">&#39;+1 week&#39;</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">);</span> 
 
<span class="c1">// Set a cookie for the whole site that expires when the browser is closed </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="m">0</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">);</span> 
 
<span class="c1">// Set a cookie for all subdomains of example.com </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="s1">&#39;+1 week&#39;</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">,</span> <span class="s1">&#39;.example.com&#39;</span><span class="p">);</span> 
 
<span class="c1">// Ensure that the cookie is only set over a secure connection (https://) </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="s1">&#39;+1 week&#39;</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">,</span> <span class="s1">&#39;.example.com&#39;</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span> 
 
<span class="c1">// Set the cookie to only be accessible to HTTP (not javascript) </span> 
<span class="nx">Cookie</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;my_value&#39;</span><span class="p">,</span> <span class="s1">&#39;+1 week&#39;</span><span class="p">,</span> <span class="s1">&#39;/&#39;</span><span class="p">,</span> <span class="s1">&#39;.example.com&#39;</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span> 
</pre></div>

<p>
Please note that set() follows the same functionality as setcookie() when it comes to deleting cookies and storing boolean values. Any value that is set to a cookie that is equal to FALSE will cause the cookie to be deleted. This means storing boolean values in a cookie is not recommended since a FALSE value will cause the cookie to be erased - instead use '0' and '1'. Also, setting the expiration date to a time in the past will cause the cookie to be deleted.
</p>

<h2>Getting Values</h2>
<p>
The static method get() replaces direct access to the $_COOKIE superglobal and adds the ability to specify default values if no cookie is found:
</p>

<div class="syntax"><pre>
<span class="c1">// Get the value &#39;default_value&#39; if no cookie of that name exists </span> 
<span class="nv">$value</span> <span class="o">=</span> <span class="nx">Cookie</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;my_cookie&#39;</span><span class="p">,</span> <span class="s1">&#39;default_value&#39;</span><span class="p">);</span> 
</pre></div> 

<p>
get() will also remove any slashes added by the ini setting magic_quotes_gpc being enabled.
</p>

<h2>Default Parameters</h2>
<p>
The Cookie_Library class also allows defining default parameters to use for set(). Since most sites will often use the same $path, $domain, $secure and $httponly parameters for cookies, setting default eliminates unnecessary duplication throughout the code. If a parameter is passed as NULL to set() and a default is defined, the default will be used. Here are the methods to set default parameters:
</p>

<table class="grid"> 
<tr>
<td> <strong>Parameter</strong> </td><td> <strong>Method</strong></td>
</tr>
<tr><td> <tt>$expires</tt></td><td>set_default_expires()</td>
</tr>
<tr><td> <tt>$path</tt></td><td>set_default_path()</td>
</tr>
<tr><td> <tt>$domain</tt></td><td>set_default_domain()</td>
</tr>
<tr><td> <tt>$secure</tt></td><td>set_default_secure()</td>
</tr>
<tr><td> <tt>$httponly</tt></td><td>set_default_httponly()</td>
</tr>
</table> 

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
