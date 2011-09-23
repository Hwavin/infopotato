<div class="container"> 

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/library/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
In InfoPotato, libraries are standalone and reusable PHP classes to be used in managers. Each library is also completely independent from the other libraries, which allows you to choose exactly which parts of the library you want to use in your application. The InfoPotato library does not dictate how you organize your application; instead, it provides you with the flexibility to decide how and when to use each component. In addition, all of the libraries' APIs are engineered in a similar way, making them as consistent as possible.
</p>

<p>
All of the available system libraries are located in your <span class="red">system/libraries</span> folder. You can also use your own libraries for each application by putting them in application libraries filder which can be defined in the single point of entry script. In most cases, to use one of these classes involves initializing it within a controller using the following initialization function:
</p> 

<p>
User defined application library must be placed at <strong>APP_LIBRARY_DIR</strong>
</p>

<h2>How to use library in Manager?</h2>
<p>
For example, to load the validation class you would do this:
</p>

<div class="syntax"><pre>
<span class="c1">// Load Form Validation library and assign post data</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;post&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">post</span><span class="p">));</span> 
</pre></div> 
 
<p>If you would like to load any application specific library, you should specify the scope as <strong>APP</strong></p>

<div class="syntax"><pre>
<span class="c1">// Load your own Form Validation library and assign post data</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;APP&#39;</span><span class="p">,</span> <span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;post&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">post</span><span class="p">));</span> 
</pre></div>  
 
<p>Once initialized you can use it as indicated in the user guide page corresponding to that class.</p> 

<h2>Creating Your Own Library</h2> 
 
<p>
Following is a very simple example which tells you how to create your own library.
</p> 
 
<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Create a user-defined library</span> 
<span class="sd"> *</span> 
<span class="sd"> * Library description goes here</span> 
<span class="sd"> */</span> 
<span class="k">class</span> <span class="nc">User_Defined_Library</span> <span class="p">{</span> 
    <span class="c1">// Class variables and constants go here</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">(</span><span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
 
    <span class="p">}</span> 
 
    <span class="c1">// Other class methods</span> 
<span class="p">}</span> 
</pre></div> 

<ul class="notebox">
<li>
All the user-defined libraries should be named like xxx_Library. All the perference parameters are passed through $config array within the constructer. If no parameters needed, just leave it blank.
</li>
<li>
The constructor and the actual library methods to be called by the manager must be declared as <tt>public</tt>.
</li>
</ul>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
