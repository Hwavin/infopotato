<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Captcha Function</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/function/">Function Reference</a> &gt; Captcha Function
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/function/captcha/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
The Captcha function will assist in creating CAPTCHA images. The captcha function requires the GD image library.
</p>

<h2>Loading this function in manager</h2>

<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;captcha/captcha_function&#39;</span><span class="p">);</span> 
</pre></div> 

<p>
Once loaded you can generate a captcha like this:
</p>

<div class="syntax"><pre>
<span class="nv">$vals</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;word&#39;</span>	 <span class="o">=&gt;</span> <span class="s1">&#39;Random word&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;img_path&#39;</span>	 <span class="o">=&gt;</span> <span class="s1">&#39;./captcha/&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;img_url&#39;</span>	 <span class="o">=&gt;</span> <span class="s1">&#39;http://example.com/captcha/&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;font_path&#39;</span>	 <span class="o">=&gt;</span> <span class="s1">&#39;./path/to/fonts/texb.ttf&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;img_width&#39;</span>	 <span class="o">=&gt;</span> <span class="s1">&#39;150&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;img_height&#39;</span> <span class="o">=&gt;</span> <span class="m">30</span><span class="p">,</span> 
    <span class="s1">&#39;expiration&#39;</span> <span class="o">=&gt;</span> <span class="m">7200</span> 
    <span class="p">);</span> 
 
<span class="nv">$cap</span> <span class="o">=</span> <span class="nx">captcha_function</span><span class="p">(</span><span class="nv">$vals</span><span class="p">);</span> 
<span class="k">echo</span> <span class="nv">$cap</span><span class="p">[</span><span class="s1">&#39;image&#39;</span><span class="p">];</span> 
</pre></div>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="sd">/**</span> 
<span class="sd"> * Create CAPTCHA</span> 
<span class="sd"> *</span> 
<span class="sd"> * @access	public</span> 
<span class="sd"> * @param	array	array of data for the CAPTCHA</span> 
<span class="sd"> * @param	string	path to create the image in</span> 
<span class="sd"> * @param	string	URL to the CAPTCHA image folder</span> 
<span class="sd"> * @param	string	server path to font</span> 
<span class="sd"> * @link		http://codeigniter.com/user_guide/helpers/xml_helper.html</span> 
<span class="sd"> * @return	string</span> 
<span class="sd"> */</span> 
<span class="k">function</span> <span class="nf">captcha_function</span><span class="p">(</span><span class="nv">$data</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$img_path</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$img_url</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="nv">$font_path</span> <span class="o">=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span> 
    <span class="c1">// The captcha function requires the GD image library.</span> 
    <span class="c1">// Only the img_path and img_url are required.</span> 
    <span class="c1">// If a &quot;word&quot; is not supplied, the function will generate a random ASCII string. </span> 
    <span class="c1">// You might put together your own word library that you can draw randomly from.</span> 
    <span class="c1">// If you do not specify a path to a TRUE TYPE font, the native ugly GD font will be used.</span> 
    <span class="c1">// The &quot;captcha&quot; folder must be writable (666, or 777)</span> 
    <span class="c1">// The &quot;expiration&quot; (in seconds) signifies how long an image will remain in the captcha folder </span> 
    <span class="c1">// before it will be deleted. The default is two hours.</span> 
 
    <span class="c1">// detail code</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>
Takes an array of information to generate the CAPTCHA as input and creates the image to your specifications, returning an array of associative data about the image.
</p>

<div class="syntax"><pre>[array]
(
  &#39;image&#39; =&gt; IMAGE TAG
  &#39;time&#39; =&gt; TIMESTAMP (in microtime)
  &#39;word&#39; =&gt; CAPTCHA WORD
)
</pre></div>

<p>
The "image" is the actual image tag:
</p>

<div class="syntax"><pre><span class="nt">&lt;img</span> <span class="na">src=</span><span class="s">&quot;http://example.com/captcha/12345.jpg&quot;</span> <span class="na">width=</span><span class="s">&quot;140&quot;</span> <span class="na">height=</span><span class="s">&quot;50&quot;</span> <span class="nt">/&gt;</span> 
</pre></div>

<p>
The "time" is the micro timestamp used as the image name without the file extension. It will be a number like this: 1139612155.3422
<br />
The "word" is the word that appears in the captcha image, which if not supplied to the function, will be a random string.
</p> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>