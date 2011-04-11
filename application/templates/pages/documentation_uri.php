<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; URI
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">URI</h1>	
<a href="http://en.wikipedia.org/wiki/Percent-encoding">http://en.wikipedia.org/wiki/Percent-encoding</a>

<H2>Types of URI characters</H2>
<p>
The characters allowed in a URI are either <i>reserved</i> or <i>unreserved</i> (or a percent character as part of a percent-encoding). <i>Reserved</i> characters are those characters that sometimes have special meaning. For example, <a href="/wiki/Forward_slash" class="mw-redirect" title="Forward slash">forward slash</a> characters are used to separate different parts of a URL (or more generally, a URI). <i>unreserved</i> characters have no such meanings. Using percent-encoding, reserved characters are represented using special character sequences. The sets of reserved and unreserved characters and the circumstances under which certain reserved characters have special meaning have changed slightly with each revision of specifications that govern URIs and URI schemes.
</p> 

<table cellpadding="6px"> 
<caption><a href="http://tools.ietf.org/html/rfc3986" class="external mw-magiclink-rfc">RFC 3986</a> section 2.2 <i>Reserved Characters</i> (January 2005)</caption> 
<tr> 
<td><code>!</code></td> 
<td><code>*</code></td> 
<td><code>'</code></td> 
<td><code>(</code></td> 
<td><code>)</code></td> 
<td><code>;</code></td> 
<td><code>:</code></td> 
<td><code>@</code></td> 
<td><code>&amp;</code></td> 
<td><code>=</code></td> 
<td><code>+</code></td> 
<td><code>$</code></td> 
<td><code>,</code></td> 
<td><code>/</code></td> 
<td><code>?</code></td> 
<td><code>#</code></td> 
<td><code>[</code></td> 
<td><code>]</code></td> 
</tr> 
</table>

<table cellpadding="6px"> 
<caption><a href="http://tools.ietf.org/html/rfc3986" class="external mw-magiclink-rfc">RFC 3986</a> section 2.3 <i>Unreserved Characters</i> (January 2005)</caption> 
<tr> 
<td><code>A</td> 
<td><code>B</code></td> 
<td><code>C</code></td> 
<td><code>D</code></td> 
<td><code>E</code></td> 
<td><code>F</code></td> 
<td><code>G</code></td> 
<td><code>H</code></td> 
<td><code>I</code></td> 
<td><code>J</code></td> 
<td><code>K</code></td> 
<td><code>L</code></td> 
<td><code>M</code></td> 
<td><code>N</code></td> 
<td><code>O</code></td> 
<td><code>P</code></td> 
<td><code>Q</code></td> 
<td><code>R</code></td> 
<td><code>S</code></td> 
<td><code>T</code></td> 
<td><code>U</code></td> 
<td><code>V</code></td> 
<td><code>W</code></td> 
<td><code>X</code></td> 
<td><code>Y</code></td> 
<td><code>Z</code></td> 
</tr> 
<tr> 
<td><code>a</code></td> 
<td><code>b</code></td> 
<td><code>c</code></td> 
<td><code>d</code></td> 
<td><code>e</code></td> 
<td><code>f</code></td> 
<td><code>g</code></td> 
<td><code>h</code></td> 
<td><code>i</code></td> 
<td><code>j</code></td> 
<td><code>k</code></td> 
<td><code>l</code></td> 
<td><code>m</code></td> 
<td><code>n</code></td> 
<td><code>o</code></td> 
<td><code>p</code></td> 
<td><code>q</code></td> 
<td><code>r</code></td> 
<td><code>s</code></td> 
<td><code>t</code></td> 
<td><code>u</code></td> 
<td><code>v</code></td> 
<td><code>w</code></td> 
<td><code>x</code></td> 
<td><code>y</code></td> 
<td><code>z</code></td> 
</tr> 
<tr> 
<td><code>0</code></td> 
<td><code>1</code></td> 
<td><code>2</code></td> 
<td><code>3</code></td> 
<td><code>4</code></td> 
<td><code>5</code></td> 
<td><code>6</code></td> 
<td><code>7</code></td> 
<td><code>8</code></td> 
<td><code>9</code></td> 
<td><code>-</code></td> 
<td><code>_</code></td> 
<td><code>.</code></td> 
<td><code>~</code></td> 
<td colspan="13"></td> 
</tr> 
</table> 

<table cellpadding="6px"> 
<caption>Reserved characters after percent-encoding</caption> 
<tr> 
<td><code>!</code></td> 
<td><code>*</code></td> 
<td><code>'</code></td> 
<td><code>(</code></td> 
<td><code>)</code></td> 
<td><code>;</code></td> 
<td><code>:</code></td> 
<td><code>@</code></td> 
<td><code>&amp;</code></td> 
<td><code>=</code></td> 
<td><code>+</code></td> 
<td><code>$</code></td> 
<td><code>,</code></td> 
<td><code>/</code></td> 
<td><code>?</code></td> 
<td><code>#</code></td> 
<td><code>[</code></td> 
<td><code>]</code></td> 
</tr> 
<tr> 
<td><code>%21</code></td> 
<td><code>%2A</code></td> 
<td><code>%27</code></td> 
<td><code>%28</code></td> 
<td><code>%29</code></td> 
<td><code>%3B</code></td> 
<td><code>%3A</code></td> 
<td><code>%40</code></td> 
<td><code>%26</code></td> 
<td><code>%3D</code></td> 
<td><code>%2B</code></td> 
<td><code>%24</code></td> 
<td><code>%2C</code></td> 
<td><code>%2F</code></td> 
<td><code>%3F</code></td> 
<td><code>%23</code></td> 
<td><code>%5B</code></td> 
<td><code>%5D</code></td> 
</tr> 
</table> 

<table cellpadding="6px"> 
<caption>Common characters after percent-encoding (ASCII or UTF-8 based)</caption> 
<tr> 
<td><code>&lt;</code></td> 
<td><code>&gt;</code></td> 
<td><code>~</code></td> 
<td><code>.</code></td> 
<td><code>"</code></td> 
<td><code>{</code></td> 
<td><code>}</code></td> 
<td><code>|</code></td> 
<td><code>\</code></td> 
<td><code>-</code></td> 
<td><code>`</code></td> 
<td><code>_</code></td> 
<td><code>^</code></td> 
<td><code>%</code></td> 
<td>space</td> 
</tr> 
<tr> 
<td><code>%3C</code></td> 
<td><code>%3E</code></td> 
<td><code>%7E</code></td> 
<td><code>%2E</code></td> 
<td><code>%22</code></td> 
<td><code>%7B</code></td> 
<td><code>%7D</code></td> 
<td><code>%7C</code></td> 
<td><code>%5C</code></td> 
<td><code>%2D</code></td> 
<td><code>%60</code></td> 
<td><code>%5F</code></td> 
<td><code>%5E</code></td> 
<td><code>%25</code></td> 
<td><code>%20</code></td> 
</tr> 
</table> 

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
