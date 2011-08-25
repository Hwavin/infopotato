<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">URI</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; URI
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/uri/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<h2>Types of URI characters</h2>
<p>
The URI is the unique address or location that identifies the resource the client wants. According to <a href="http://en.wikipedia.org/wiki/Percent-encoding" class="external_link">Wikipedia</a>: The characters allowed in a URI are either <i>reserved</i> or <i>unreserved</i> (or a percent character as part of a percent-encoding). <i>Reserved</i> characters are those characters that sometimes have special meaning. For example, forward slash characters are used to separate different parts of a URL (or more generally, a URI). <i>Unreserved</i> characters have no such meanings. Using percent-encoding, reserved characters are represented using special character sequences. The sets of reserved and unreserved characters and the circumstances under which certain reserved characters have special meaning have changed slightly with each revision of specifications that govern URIs and URI schemes.
</p> 

<table cellpadding="6px" class="grid"> 
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

<table cellpadding="6px" class="grid"> 
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

<table cellpadding="6px" class="grid"> 
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

<table cellpadding="6px" class="grid"> 
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

<h2>Specifying the Permitted URI characters</h2>
<p>
You can specify the permitted URI characters in the entry script index.php.
</p>

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Default allowed URL Characters (UTF-8 encoded characters)</span> 
<span class="sd"> *</span> 
<span class="sd"> * By default only these are allowed: a-z 0-9~%.:_-</span> 
<span class="sd"> * Leave blank to allow all characters</span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;PERMITTED_URI_CHARS&#39;</span><span class="p">,</span> <span class="s1">&#39;a-z 0-9~%.:_-&#39;</span><span class="p">);</span> 
</pre></div> 

<h2>InfoPotato's URI mapping to manager methods</h2>
<p>
A clean, elegant URI scheme is an important detail in a high-quality Web application. InfoPotato encourages beautiful URI design and doesn't put any cruft in URIs, like .php. URIs in InfoPotato are composed of segments. A typical segmented URI follows this pattern:
</p>

<div class="syntax">
http://www.example.com/index.php/<span class="red">manager</span>/<span class="blue">method</span>/<span class="green">param1</span>/<span class="green">param2/<span class="green">param3/</span>
</div>

<p>
The segments correspond (in order ) to a <span class="red">Manager</span>, a <span class="blue">Manager method</span>, and the <span class="green">method parameters</span>. There is a one-to-one relationship between a URI string and its corresponding manager class/method. Part of the advantage of using InfoPotato is that it has a very clear and understandable URI mapping structure. Given a URI the programmer knows exactly which function is being run in which file and which parameters are being passed to that function. This makes InfoPotato one of the best environments to develop in especially for groups of programmers working on the same code base.
</p>

<p>
To make the update to the URI in the templates easiler, InfoPotato provides the developers the option to define <strong>APP_URI_BASE</strong> as the base URI of all the application requests. Forr example:
</p>

<div class="syntax"><pre>
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;APP_URI_BASE&#39;</span><span class="p">,</span> <span class="s1">&#39;http://localhost/infopotato/web/index.php/&#39;</span><span class="p">);</span> 
</pre></div> 

<h2>Managing Static Resources</h2>

<p>
Web developers mostly concern themselves with the dynamic parts of web applications – the views and templates that render anew for each request. But web applications have other parts: the static files (images, CSS, Javascript, etc.) that are needed to render a complete web page.
</p>

<p>
InfoPotato provides the developers the option to define a <strong>STATIC_URI_BASE</strong> for accessing to their static resources.
</p>

<div class="syntax"><pre>
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;STATIC_URI_BASE&#39;</span><span class="p">,</span> <span class="s1">&#39;http://localhost/infopotato/web/&#39;</span><span class="p">);</span> 
</pre></div> 

<div class="notebox">
If you are serving static files from a cloud storage provider like Amazon's S3 and/or a CDN (content delivery network), just set <strong>STATIC_URI_BASE</strong> to the target URI base.
</div>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
