<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Manager</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Manager
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/manager/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Managers are the heart of your application, as they determine how HTTP requests should be prepared and handled as well as the HTTP responses. In other words, managers exectue the application's domain logic.
</p>

<h2>What is a Manager?</h2>

<p>
A Manager is an instance of the base Manager object or a subclass of another manager. A Manager is used to manage the logic for a part of your application. A Manager is simply a class file that is named in a way that can be associated with a URI. When a Manager runs, it performs the requested method which usually brings in the needed datas and produces the output in the proper format (whether that's a PHP+HTML/HTML page, XML document, or JSON response). An method, at its simplest form, is just a Manager class method prefixed with the corresponding HTTP request method (InfoPotato only supports GET and POST). In a word, the manager contains whatever arbitrary logic your application needs to create that response.
</p>

<p>
Typically, a Manager is responsible for a few things:
</p>

<ul>
<li>processing GET/POST input variables</li>
<li>loading libraries and user-defined functions</li>
<li>loading data objects for SQL or NoSQL data access</li>
<li>validating authentication and access levels</li>
<li>interacting with data objects (business logic)</li>
<li>assigning template variables and "push" the data to the template to render the output result.</li>
<li>rendering templates or redirecting to new URIs</li>
</ul>

<p>
A Manager has a default method. When the user request does not specify which method to execute, the default method will be executed. You can set the DEFAULT_MANAGER_METHOD in index.php.
</p>

<p>Consider this URI:</p>

<p class="red">http://www.example.com/index.php/<var>blog</var>/</p>

<p>In the above example, InfoPotato would attempt to find a Manager named <dfn>blog_manager.php</dfn> and load it.</p>

<p><strong>When a Manager's name matches the first segment of a URI, it will be loaded.</strong></p>


<h2>Manager Naming and Anatomy</h2>
<p>
A manager's filename can be basically anything. The name of the manager class must correspond to the filename.
</p>

<ul>
<li>must reside in the managers directory</li>
<li>manager filename must be lowercase, e.g. blog_manager.php</li>
<li>manager class must map to filename and capitalized, and must be appended with _Manager, e.g. Blog_Manager</li>
<li>must have the Manager class as (grand)parent</li>
<li>manager methods preceded by '_' (e.g. _do_something() ) cannot be called by the URI mapping</li>
<li>web-accessible manager methods are prefixed with either "get_" or "post", depending on the HTTP request method (GET/POST).</li>
</ul>


<h2>Method Conventions - RESTful by Default</h2>
<p>
InfoPotato was designed with REST in mind from the beginning. The "{request method}_{URI method segment}" manager methods are special because they can only be accessed by using the corresponding HTTP request method. This semantic difference enables true RESTful behavior, because it separates processing actions from requesting ones, while still allowing you to use the same URI.
</p>

<p>
To elucidate, let's begin with a simple example of a contact manager (application/managers/contact_manager.php).
</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Conatct_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Display the contact form</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_index</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Process the submitted contact data</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<p>
Notice that these two methods are prefixed with the type of HTTP request that was issued. This separation concept is adopted from the WebPY framework and its subsequent PHP clone, WebPHP.
</p>

<div class="notebox">
<pre>
<strong>GET /contact/</strong>
Manager: application/managers/contact_manager.php
Method: get_index()

<strong>POST /contact/</strong>
Manager: application/managers/contact_manager.php
Method: post_index()
</pre>
</div>



<h2>Defining a Default Manager</h2>

<p>InfoPotato can be told to load a default Manager when a URI is not present,
as will be the case when only your site root URL is requested.  To specify a default Manager, open
your <dfn>index.php</dfn> file and set this variable:</p>

<div class="syntax"><pre>
<span class="sd">/**</span> 
<span class="sd"> * Default Manager/method if none is given in the URL, case-sensetive </span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;DEFAULT_MANAGER&#39;</span><span class="p">,</span> <span class="s1">&#39;home&#39;</span><span class="p">);</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;DEFAULT_MANAGER_METHOD&#39;</span><span class="p">,</span> <span class="s1">&#39;index&#39;</span><span class="p">);</span> 
</pre></div> 

<p>Where <var>blog</var> is the name of the Manager class you want used. If you now load your main index.php file without
specifying any URI segments you'll see your Hello World message by default.</p>


<h2>Dealing with $_GET, $_POST, and $_COOKIE</h2>

<p>
While using InfoPotato, you would never use $_GET, $_POST, and $_COOKIE. $_GET data is disallowed since InfoPotato utilizes URI segments rather than traditional URL query strings. Instead we take use of the $POST_DATA and $COOKIE_DATA variables which can only be accessed in managers
</p>


<h2>Common Manager Tasks</h2>

<p>
Though a manager can do virtually anything, most managers will perform the same basic tasks over and over again. These tasks, such as redirecting, forwarding, rendering templates and accessing core services, are very easy to manage in InfoPotato.
</p>

<h3>Redirecting</h3>

<p>
If you want to redirect the user to another page, load the <a href="<?php echo APP_URI_BASE; ?>documentation/function/redirect/"><tt>redirect_function()</tt></a>.
</p>

<p class="notebox">
Redirection is common but not as frquently used as template rendering, so it is not designed as a manager method but a function to be loaded on demaind.
</p>

<div class="notebox">
<strong>What is the difference between redirect and forward?</strong><br />
<p>
Imagine you get a phone call in the office. If you say "please call sales at 123456" and hang up, this is redirect . If you say "wait a minute" and just transfer the call to them, this is forward. 
</p>

<p>
In other words, a redirect sends a 301/302 back to the browser with a new URI, while a forward simply "forwards" the request to a different manager method internally but keeps the URI the same so the browser doesn't know any different.
</p>

<p>
InfoPotato doesn't provide any potions for internal forwarding.
</p>
</div>

<h3>Rendering Templates</h3>

<p>
Though not a requirement, most managers will ultimately render a template that's responsible for generating the HTML (or other format) for the manager. The Manager has a render_template() method that takes care of sending your final rendered data to the client automatically. In some cases, however, you might want to
post-process the finalized data in some way and send it to the browser yourself. InfoPotato also permits you to
use <dfn>render_template()</dfn> to your Manager that will receive the finalized output data.
</p>

<p>
Here is an example:
</p>

<div class="syntax"><pre>
<span class="k">function</span> <span class="nf">get_entry</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;data&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;some data&#39;</span><span class="p">,</span> 
    <span class="p">);</span> 
 
    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;New Time Entry&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/new_entry_activity_one&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
    <span class="p">);</span> 
 
    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/layout&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
    <span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div> 


<h3>Loading data</h3>

<p>The load_data() function comes handy when you need to use a data.</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">User_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
        <span class="c1">// Load user data, this data can be used by other class methods</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;user_data&#39;</span><span class="p">,</span> <span class="s1">&#39;u&#39;</span>);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_user_posts</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load post data, this data can only be used by this class method</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;post_data&#39;</span><span class="p">,</span> <span class="s1">&#39;p&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h3>Loading Library</h3>

<p>The load_library() function comes handy when you need to use a library class.</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">About_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
        <span class="c1">// Load Cache library, this library can be used by other class methods</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;cache/cache_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cache&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;cache_dir&#39;</span><span class="o">=&gt;</span><span class="nx">APP_CACHE_DIR</span><span class="p">));</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_email</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load Form Validation library, this library can only be used by this class method</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;post&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">));</span>
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h2>Private Functions</h2>


<p>
In some cases you may want certain functions hidden from public access.  To make a function private, simply add an underscore as the name prefix and it will not be served via a URL request. For example, if you were to have a class function like this:
</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Conatct_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_send_email</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Code goes here</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<p>Trying to access it via the URL, like this, will not work:</p>

<div class="syntax">
example.com/index.php/contact/_send_email/
</div>


<h2>Special Manager Methods</h2>

<h3>__construct()</h3>
<p>
If you'd like to run some code in every method, a great place to do it is inside your controller's constructor method. Now, every time you run your controller, the __construct code will get run as well. If you declare a constructor in your manager, for example to load some resources for the entire manager, you have to call the parent constructor.
</p>

<code>parent::__construct();</code>

<p>The reason this line is necessary is because your local constructor will be overriding the one in the parent Manager class so we need to manually call it.</p>

<p>In PHP 5, constructors use the following syntax:</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
       <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
            <span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
       <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<p>Constructors are useful if you need to set some default values, or run a default process when your class is instantiated.
Constructors can't return a value, but they can do some default work.</p>

<h3>__call()</h3>

<p>
If a user calls a controller and doesn't specify a method to run, the controller will throw a 404 method by default; however, by using the __call method (a PHP magic method), you can override the 404 and display your own message or even forward the user to a default controller.
</p>

<h2>Reserved Function Names</h2>

<p>Since your Manager classes will extend the main application Manager you
must be careful not to name your functions identically to the ones used by that class, otherwise your local functions
will override them. See <a href="reserved_names.html">Reserved Names</a> for a full list.
</p>

<h2>Storing Managers within Sub-folders</h2> 
<p class="notebox">
By default, your data files and template files can also be stored within sub-folders if you prefer that type of organization. But Manager files is NOT ALLOWED to be organized by sub-folders since they are directly related to the URI path pattern.
</p> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
