<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Template
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Template</h1>	

<p>
Templates serve as the presentation layer of InfoPotato. A template is a PHP script consisting of mainly elements of user interface (e.g., an HTML page fragment, an XML document, a serialized JSON array). In fact, templates can flexibly be embedded within other templates (within other templates, etc., etc.) if you need this type of hierarchy. It can contain PHP statements, but it is recommended that these statements should remain relatively simple. For the spirit of separation of logic and presentation, large chunk of logic should be placed in manager instead of template.
</p>

<p>Templates are never called directly, they must be loaded by a manager. Remember that in InfoPotato, the manager acts as the traffic cop, so it is responsible for loading and rendering a particular template.</p> 
 
<div class="tipbox">
<p>
By default InfoPotato buffers all HTML generated from templates inside the execution context and sends a complete page once the template execution has completed (via the render_template() method). The advantage of buffering the HTML is that applications can handle exceptions that occur during execution of the template and prevent any partial results leaking to the browser.
</p>
</div>

<h2>Creating a Template</h2> 
 
<p>Using your text editor, create a file called blog.php, and put this in it:</p> 
 
<div class="syntax">
<pre><span class="nt">&lt;html&gt;</span> 
<span class="nt">&lt;head&gt;</span> 
<span class="nt">&lt;title&gt;</span>My Blog<span class="nt">&lt;/title&gt;</span> 
<span class="nt">&lt;/head&gt;</span> 
<span class="nt">&lt;body&gt;</span> 
<span class="nt">&lt;h1&gt;</span>Welcome to my Blog!<span class="nt">&lt;/h1&gt;</span> 
<span class="nt">&lt;/body&gt;</span> 
<span class="nt">&lt;/html&gt;</span> 
</pre>
</div> 
 
<p>Then save the file in your <dfn>application/templates/</dfn> folder.</p> 
 
<h2>Loading a Template</h2> 
 
<p>To load a particular template file you will use the following function:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;name&#39;</span><span class="p">);</span> 
</pre>
</div> 
 
<p>Where name is the name of your template file.  Note: The .php file extension does not need to be specified unless you use something other than <kbd>.php</kbd>.</p> 
 
 
<p>Now, open the controller file you made earlier called blog_template.php, and replace the echo statement with the template loading function:</p> 
 
<div class="syntax">
<pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;blog_template&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 
 
 
<p>If you visit your site using the URL you did earlier you should see your new template.  The URL was similar to this:</p> 
 
<code>example.com/index.php/<var>blog</var>/</code> 
 
<p class="important"><strong>Note:</strong> You can also put the template file in sub-folders, like "pages/blog.php". Then you just change the code to:

<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/blog&#39;</span><span class="p">);</span> 
</pre>
</div> 
 
</p> 
 
<h2>Storing Templates within Sub-folders</h2> 
<p>Your template files can also be stored within sub-folders if you prefer that type of organization.  When doing so you will need
to include the folder name loading the template.  Example:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;folder_name/file_name&#39;</span><span class="p">);</span> 
</pre>
</div> 
 
 
<h2>Adding Dynamic Data to the Template</h2> 
 
<p>Data is passed from the controller to the template by way of an <strong>array</strong> or an <strong>object</strong> in the second
parameter of the template loading function. Here is an example using an array:</p> 
 
<div class="syntax">
<pre>
<span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;My Title&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;heading&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;My Heading&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;message&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;My Message&#39;</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/blog_template&#39;</span><span class="p">,</span> <span class="nv">$data</span><span class="p">);</span> 
</pre></div> 

<h2>Returning templates as data</h2> 
 
<div class="syntax">
<pre>
<span class="nv">$var</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;my_file&#39;</span><span class="p">);</span> 
</pre>
</div>  
 
 
<h2>Assign global variables to template</h2> 
 
<p> 
The variables assigned with $this->assign() are always available to every template thereafter.
</p> 
 
<div class="syntax">
<pre>
<span class="k">protected</span> <span class="k">function</span> <span class="nf">_check_admin_auth</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">assign_template_global</span><span class="p">(</span><span class="s1">&#39;admin_fullname&#39;</span><span class="p">,</span> <span class="nv">$_SESSION</span><span class="p">[</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="p">][</span><span class="s1">&#39;fullname&#39;</span><span class="p">]);</span>	
<span class="p">}</span> 
</pre>
</div> 
 
<h2>Output the rendered template to browser</h2> 
 
<div class="syntax">
<pre><span class="cp">&lt;?php</span> 
<span class="sd">/**</span> 
<span class="sd">* Output the rendered template to browser</span> 
<span class="sd">* </span> 
<span class="sd">* @param array $config options</span> 
<span class="sd">* </span> 
<span class="sd">* &#39;content&#39;: (string required) content to be compressed</span> 
<span class="sd">* </span> 
<span class="sd">* &#39;type&#39;: (string) if set, the Content-Type header will have this value.</span> 
<span class="sd">*</span> 
<span class="sd">* &#39;minify_html&#39;: (boolean) if set, the html content will be minified before compression.</span> 
<span class="sd">*</span> 
<span class="sd">* &#39;compression_level&#39;: (int) </span> 
<span class="sd">* </span> 
<span class="sd">* &#39;method: (string) only set this if you are forcing a particular encoding</span> 
<span class="sd">* method. If not set, the best method will be chosen by get_accepted_encoding()</span> 
<span class="sd">* The available methods are &#39;gzip&#39;, &#39;deflate&#39;, &#39;compress&#39;, and &#39;&#39; (no encoding)</span> 
<span class="sd">* </span> 
<span class="sd">* @return NULL</span> 
<span class="sd">*/</span> 
<span class="k">public</span> <span class="k">function</span> <span class="nf">response</span><span class="p">(</span><span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
    <span class="c1">// code</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre>
</div>  

<p>In controller file, you can render and output the template using the following code:</p>

<div class="syntax">
<pre>
<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/layout_template&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
    <span class="s1">&#39;content_type&#39;</span> <span class="o">=&gt;</span> <span class="k">'text/html'</span><span class="p">,</span> 
<span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
</pre>
</div> 
 
<h2>Layout</h2> 
<p>
Layout is a special template that is used to decorate templates. It usually contains portions of user interface that are common among several templates. For example, a layout may contain header and footer portions and embed the content template in between,
</p>

<div class="syntax"><pre><span class="nt">&lt;div&gt;</span>header here<span class="nt">&lt;/div&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$content</span><span class="p">;</span><span class="cp">?&gt;</span> 
<span class="nt">&lt;div&gt;</span>footer here<span class="nt">&lt;/div&gt;</span> 
</pre></div> 

<p>
As you can see, all the "outer" stuff gets thrown in the layout. When the content is rendered within a layout, it will be substituted for the variable $content, which stores the rendering result of the content template.
</p>

</div> 
<!-- end onecolumn -->
