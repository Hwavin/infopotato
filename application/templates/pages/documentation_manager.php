<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Manager
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Manager</h1>	

<p>Managers are the heart of your application, as they determine how HTTP requests should be prepared and handled as well as the HTTP responses.</p>

<h2>What is a Manager?</h2>

<p>A Manager is an instance of the base Manager object or a subclass of another manager. A Manager is used to manage the logic for a part of your application. A Manager is simply a class file that is named in a way that can be associated with a URI. When a Manager runs, it performs the requested method which usually brings in the needed datas and produces the output in the proper format (whether that's a PHP+HTML/HTML page, XML document, or JSON response). An method, at its simplest form, is just a Manager class method prefixed with the corresponding HTTP request method (InfoPotato only supports GET and POST). In a word, the manager contains whatever arbitrary logic your application needs to create that response.</p>

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

<h2>Let's try it:&nbsp; Hello World!</h2>

<p>Let's create a simple Manager so you can see it in action. Using your text editor (Mine is Notepad++), create a file called <dfn>blog_manager.php</dfn>, and put the following code in it:</p>

<div class="syntax">
<pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
	<span class="c1">// Ohter codes </span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">echo</span> <span class="s1">&#39;Hello World!&#39;</span><span class="p">;</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"> </span> 
</pre>
</div> 

<p>Then save the file to your <dfn>application/Managers/</dfn> folder.</p>

<p>Now visit the your site using a URL similar to this:</p>

<p class="red">example.com/index.php/<var>blog</var>/</p>

<p>If you did it right, you should see <samp>Hello World!</samp>.</p>

<p>Also, always make sure your Manager <dfn>extends</dfn> the parent Manager class so that it can inherit all its functions.</p>


<h2>Functions</h2>

<p>In the above example the function name is <dfn>index()</dfn>.  The "index" function is always loaded by default if the
<strong>second segment</strong> of the URI is empty.  Another way to show your "Hello World" message would be this:</p>

<code>example.com/index.php/<var>blog</var>/<samp>index</samp>/</code>

<p><strong>The second segment of the URI determines which function in the Manager gets called.</strong></p>

<p>Let's try it.  Add a new function to your Manager:</p>


<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
 
	<span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
		<span class="k">echo</span> <span class="s1">&#39;Hello World!&#39;</span><span class="p">;</span> 
	<span class="p">}</span> 
 
	<span class="k">function</span> <span class="nf">get_comments</span><span class="p">()</span> <span class="p">{</span> 
		<span class="k">echo</span> <span class="s1">&#39;Look at this!&#39;</span><span class="p">;</span> 
	<span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>Now load the following URL to see the <dfn>comment</dfn> function:</p>

<code>example.com/index.php/<var>blog</var>/<samp>comments</samp>/</code>

<p>You should see your new message.</p>


<h2>Passing URI Segments to your Functions</h2>

<p>If your URI contains more then two segments they will be passed to your function as parameters.</p>

<p>For example, lets say you have a URI like this:</p>

<code>example.com/index.php/<var>products</var>/<samp>shoes</samp>/<kbd>sandals</kbd>/<dfn>123</dfn></code>

<p>Your function will be passed URI segments 3 and 4 ("sandals" and "123"):</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
	<span class="c1">// Ohter codes </span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">process</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">echo</span> <span class="s1">&#39;Hello World!&#39;</span><span class="p">;</span> 
    <span class="p">}</span> 
 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_date</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nb">date</span><span class="p">();</span> 
    <span class="p">}</span> 
 
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_get_user</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">echo</span> <span class="s1">&#39;Joe&#39;</span><span class="p">;</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"> </span> 
</pre></div> 


<h2>Defining a Default Manager</h2>

<p>InfoPotato can be told to load a default Manager when a URI is not present,
as will be the case when only your site root URL is requested.  To specify a default Manager, open
your <dfn>index.php</dfn> file and set this variable:</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="sd">/**</span> 
<span class="sd"> * Default Manager/method if none is given in the URL, case-sensetive </span> 
<span class="sd"> */</span> 
<span class="nb">define</span><span class="p">(</span><span class="s1">&#39;DEFAULT_WORKER&#39;</span><span class="p">,</span> <span class="s1">&#39;home&#39;</span><span class="p">);</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 

<p>Where <var>blog</var> is the name of the Manager class you want used. If you now load your main index.php file without
specifying any URI segments you'll see your Hello World message by default.</p>


<h2>Dealing with $_GET, $_POST, and $_COOKIE</h2>

<p>
While using InfoPotato, you would never use $_GET, $_POST, and $_COOKIE. $_GET data is disallowed since InfoPotato utilizes URI segments rather than traditional URL query strings. Instead we take use of the $POST_DATA and $COOKIE_DATA variables which can only be accessed in managers
</p>

<h2>Interacting with Template</h2>

<p>InfoPotato has an view class that takes care of sending your final rendered data to the web browser automatically.  More information on this can be found in the
<strong><a href="<?php echo APP_URI_BASE; ?>documentation/view/" title="View">View</a></strong> class</a> pages.  In some cases, however, you might want to
post-process the finalized data in some way and send it to the browser yourself. InfoPotato permits you to
add a function named <dfn>load_template()</dfn> to your Manager that will receive the finalized output data.</p>

<p>Here is an example:</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">function</span> <span class="nf">process</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;data&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;some data&#39;</span><span class="p">,</span> 
    <span class="p">);</span> 
 
    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;New Time Entry&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;pages/new_entry_activity_one&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
    <span class="p">);</span> 
 
    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;layouts/layout&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
    <span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div> 


<h2>Private Functions</h2>


<p>In some cases you may want certain functions hidden from public access.  To make a function private, simply add an
underscore as the name prefix and it will not be served via a URL request. For example, if you were to have a function like this:</p>

<code>
function _utility() {<br />
&nbsp;&nbsp;// some code<br />
}</code>

<p>Trying to access it via the URL, like this, will not work:</p>

<code>example.com/index.php/<var>blog</var>/<samp>_utility</samp>/</code>


<h2>Class Constructors</h2>


<p>If you intend to use a constructor in any of your Managers, you <strong>MUST</strong> place the following line of code in it:</p>

<code>parent::Manager();</code>

<p>The reason this line is necessary is because your local constructor will be overriding the one in the parent Manager class so we need to manually call it.</p>

<p>In PHP 5, constructors use the following syntax:</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Blog_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
 
       <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> 
       <span class="p">{</span> 
            <span class="k">parent</span><span class="o">::</span><span class="na">Manager</span><span class="p">();</span> 
       <span class="p">}</span> 
<span class="p">}</span> 
<span class="cp">?&gt;</span><span class="x"></span> 
</pre></div>

<p>Constructors are useful if you need to set some default values, or run a default process when your class is instantiated.
Constructors can't return a value, but they can do some default work.</p>


<h2>Reserved Function Names</h2>

<p>Since your Manager classes will extend the main application Manager you
must be careful not to name your functions identically to the ones used by that class, otherwise your local functions
will override them. See <a href="reserved_names.html">Reserved Names</a> for a full list.</p>

<h2>Load data</h2>

<p>The load_data() function comes handy when you need to use a data.</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">User_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
        <span class="c1">// Load user data, this data can be used by other class methods</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;user_data&#39;</span><span class="p">,</span> <span class="s1">&#39;u&#39;</span>);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_user_post</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load post data, this data can only be used by this class method</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;post_data&#39;</span><span class="p">,</span> <span class="s1">&#39;p&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h2>Load Library</h2>

<p>The load_library() function comes handy when you need to use a library class.</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">About_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
        <span class="c1">// Load Cache library, this library can be used by other class methods</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;cache/cache_library&#39;</span><span class="p">,</span> <span class="s1">&#39;cache&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;cache_dir&#39;</span><span class="o">=&gt;</span><span class="nx">APP_CACHE_DIR</span><span class="p">));</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">send_email</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load Form Validation library, this library can only be used by this class method</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h2>Storing Managers within Sub-folders</h2> 
<p class="tipbox">
By default, your data files and template files can also be stored within sub-folders if you prefer that type of organization. But Manager files is NOT ALLOWED to be organized by sub-folders.
</p> 
 
 
</div> 
<!-- end onecolumn -->
