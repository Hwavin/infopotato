<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Type Casting In InfoPotato &mdash; What's the Point?</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/">Tutorials</a> &gt; Type Casting In InfoPotato &mdash; What's the Point?
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/tutorial/typecasting/'); ?>" class="print">Print</a>

<!-- PRINT: start -->

<p>
PHP does not require (or support) explicit type definition in variable declaration; a variable's type is determined by the context in which the variable is used. That is to say, if a string value is assigned to variable $var, $var becomes a string. If an integer value is then assigned to $var, it becomes an integer. Did you know that PHP has some pretty powerful type casting functionality built-in? It's no surprise if you comprehend the roots of PHP (since it's written in C), but I can't help but think that casting is an often-missed tool when a PHP developer is trying to ensure data integrity.
</p>

<p>
Type casting in PHP works much as it does in C: the name of the desired type is written in parentheses before the variable which is to be cast.
</p>

<p><strong>The following cast types are allow in PHP:</strong></p>
<ul>
<li>String &#8211; (string)</li>
<li>Boolean &#8211; (bool), (boolean)</li>
<li>Integer &#8211; (int), (integer)</li>
<li>Binary &#8211; (binary) [PHP 6]</li>
<li>Floating Point &#8211; (float), (double), (real)</li>
<li>Array &#8211; (array)</li>
<li>Object &#8211; (object)</li>
</ul>

<h2>When does casting actually come in handy? </h2>

<p>
Normally, PHP handles all this stuff automatically behind the scenes. But, as is normal, dealing with MySQL database interaction is something to always take seriously &mdash; and type casting can help you out!
</p>

<p>
When dealing with data from URL or posted forms, you need to pay attention to the integer/float, since PHP only takes them as string.
</p>

<p>
To explicitly convert a value to integer, use either the (int) or (integer) casts. However, in most cases the cast is not needed, since a value will be automatically converted if an operator, function or control structure requires an integer argument. A value can also be converted to integer with the <a href="http://www.php.net/manual/en/function.intval.php" class="external_link"><code class="php_function">intval()</code></a> function.
</p>

<p>
Anyways, as you can see, type casting in PHP has real-world uses. Delve into type casting a little more and you’ll find a huge number of cases where it can make your code that much more bullet-proof.
</p>

<!-- PRINT: stop -->