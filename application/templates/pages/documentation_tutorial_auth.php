<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Authentication and Authorization</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/">Tutorials</a> &gt; Authentication and Authorization
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/tutorial/auth/'); ?>" class="print">Print</a>

<!-- PRINT: start -->

<p>
Authentication and authorization are required for a Web page that should be limited to certain users. Authentication is about verifying whether someone is who they claim to be. It usually involves a username and a password, but may include any other methods of demonstrating identity, such as a smart card, fingerprints, etc. Authorization is finding out if the person, once identified (i.e. authenticated), is permitted to manipulate specific resources. This is usually determined by finding out if that person is of a particular role that has access to the resources. InfoPotato provides the developers two types of implimentation of authorization.
</p>

<div class="box_right greybox">
<blockquote>
<span>Knowledge is power, guard it well.</span>
<div>&mdash; Warhammer 40,000: Dawn of War</div>
</blockquote>
</div>

<ul>
<li><strong>Simple Authorization</strong> &mdash; Each user has to login to view any page that requires authorization</li>
<li><strong>Authorization Levels</strong> &mdash; Each user is assigned a single authorization level and can view any page that requires that level or a level below.</li>
</ul>


<h2>Login Form Template</h2>

<p>
As mentioned above, authentication is about validating the identity of the user. A typical Web application authentication implementation usually involves using a username and password combination to verify a user's identity. It will often be desirable to provide an option for a user to stay logged in even after their browser closes. Obviously this can be a security issue, however many large websites control the functionality via a checkbox in the login form that is labelled "Keep me logged in." This will usually keep a user logged in for a week or two.
</p>

<div class="syntax">
<pre><span class="nt">&lt;form</span> <span class="na">method=</span><span class="s">&quot;post&quot;</span> <span class="na">action=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">simple_auth/login/&quot;</span><span class="nt">&gt;</span> 
 
<span class="nt">&lt;p&gt;</span> 
Username and password are required.
<span class="nt">&lt;/p&gt;</span> 
 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$auth</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="nv">$auth</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="s1">&#39;Incorrect Username/Password Combination&#39;</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
   
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;text&quot;</span> <span class="na">name=</span><span class="s">&quot;username&quot;</span> <span class="na">value=</span><span class="s">&quot;&quot;</span> <span class="nt">/&gt;</span>  
  
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;password&quot;</span> <span class="na">name=</span><span class="s">&quot;password&quot;</span> <span class="na">value=</span><span class="s">&quot;&quot;</span> <span class="nt">/&gt;</span>  
 
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;checkbox&quot;</span> <span class="na">name=</span><span class="s">&quot;auto_login&quot;</span> <span class="na">value=</span><span class="s">&quot;1&quot;</span> <span class="nt">/&gt;</span>Keep me logged in for a week
 
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;submit&quot;</span> <span class="na">name=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">value=</span><span class="s">&quot;Login&quot;</span> <span class="nt">/&gt;</span> 
 
<span class="nt">&lt;/form&gt;</span> 
</pre></div>

<div class="notebox">
If you are using Authorization Levels, you will need to change the form action to "level_auth/login/"
</div>

<h2>Logout</h2>

<div class="syntax">
<pre><span class="nt">&lt;a</span> <span class="na">href=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">simple_auth/logout/&quot;</span> <span class="na">title=</span><span class="s">&quot;Logout&quot;</span><span class="nt">&gt;</span>[Logout]<span class="nt">&lt;/a&gt;</span> 
</pre></div>

<div class="notebox">
If you are using Authorization Levels, you will need to change the logout link to "level_auth/logout/"
</div>

<h2>Simple Authorization</h2>

<p>
User authentication systems are a common part of many web applications but not required by every application. Considering this, in InfoPotato, the actual implimentation of the authentication can be done by a user created manager, let's say Simple_Auth_Manager.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Simple_Auth_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="c1">// Define session prefix</span> 
    <span class="k">const</span> <span class="no">SESSION_KEY</span> <span class="o">=</span> <span class="s1">&#39;user::&#39;</span><span class="p">;</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * The logged in user fullname to be displayed in layout template</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var string</span> 
<span class="sd">     */</span> 
    <span class="k">protected</span> <span class="nv">$_user_fullname</span><span class="p">;</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * User data</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var array</span> 
<span class="sd">     */</span> 
     <span class="k">private</span> <span class="nv">$_user</span><span class="p">;</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Constructor</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// A call to parent::__construct() within the child constructor is required</span> 
        <span class="c1">// to make the $this-&gt;POST_DATA available</span> 
        <span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
 
        <span class="c1">// Get the layout variable</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Checks if a user has logged in</span> 
<span class="sd">     */</span> 
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_require_auth</span><span class="p">()</span> <span class="p">{</span> 
        <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_user_token</span><span class="p">())</span> <span class="p">{</span> 
            <span class="c1">// Remember the URI requested before the user was redirected to the login page</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_set_requested_uri</span><span class="p">(</span><span class="s1">&#39;http://&#39;</span><span class="o">.</span><span class="nv">$_SERVER</span><span class="p">[</span><span class="s1">&#39;SERVER_NAME&#39;</span><span class="p">]</span><span class="o">.</span><span class="nv">$_SERVER</span><span class="p">[</span><span class="s1">&#39;REQUEST_URI&#39;</span><span class="p">]);</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_login</span><span class="p">();</span> 
            <span class="k">exit</span><span class="p">;</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * This method must be declaired as private to make it inaccessible directly from /auth/login/</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_login</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/login&#39;</span><span class="p">),</span> 
        <span class="p">);</span> 
		
        <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/login&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
            <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
        <span class="p">);</span> 
 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * This method must be declaired as public</span> 
<span class="sd">     * so that it&#39;s accessible directly to the login form&#39;s post action - /auth/login/</span> 
<span class="sd">     * Since it&#39;s a POST request, no access directly from the URI</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_login</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$username</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
        <span class="nv">$password</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
        <span class="nv">$auto_login</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;auto_login&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;auto_login&#39;</span><span class="p">]</span> <span class="o">:</span> <span class="m">0</span><span class="p">;</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_identify</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="o">===</span> <span class="k">TRUE</span><span class="p">)</span> <span class="p">{</span> 
            <span class="c1">// Store some user data in Session</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;uid&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
			
            <span class="c1">// Set the user fullname of logged in user to be used in layout template</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;fullname&#39;/span><span class="p">]);</span> 
 
            <span class="c1">// Set the user token</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_set_user_token</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
 
            <span class="c1">// Keep me logged in</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$auto_login</span> <span class="o">===</span> <span class="s1">&#39;1&#39;</span><span class="p">)</span> <span class="p">{</span> 
                <span class="nx">Session</span><span class="o">::</span><span class="na">enable_persistence</span><span class="p">();</span> 
            <span class="p">}</span> 
 
            <span class="c1">// Sometimes when a user visits the login page, they will have entered the URI manually, </span> 
            <span class="c1">// or will have followed a link. In this sort of situation you will need a default page </span> 
            <span class="c1">// to redirect them to. The rest of the time users will usually get directed to the login page </span> 
            <span class="c1">// because they tried to access a restricted page.</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;redirect/redirect_function&#39;</span><span class="p">);</span> 
            <span class="nx">redirect_function</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_requested_uri</span><span class="p">(</span><span class="k">TRUE</span><span class="p">,</span> <span class="nx">APP_URI_BASE</span><span class="o">.</span><span class="s1">&#39;agreement/&#39;</span><span class="p">));</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
            <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;auth&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span><span class="p">,</span> 
            <span class="p">);</span> 
			
            <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
                <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/login&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
            <span class="p">);</span> 
			
            <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/login&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
                <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
            <span class="p">);</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
        <span class="p">}</span> 
		
		
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Logout and destroy user&#39;s session data</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_logout</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Clear stored user session data</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">clear</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="p">);</span> 
 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;redirect/redirect_function&#39;</span><span class="p">);</span> 
        <span class="nx">redirect_function</span><span class="p">(</span><span class="nx">APP_URI_BASE</span><span class="o">.</span><span class="s1">&#39;home/&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Identifies a user based on username/password</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_identify</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;iteration_count_log2&#39;</span> <span class="o">=&gt;</span> <span class="m">8</span><span class="p">,</span> 
            <span class="s1">&#39;portable_hashes&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span> 
        <span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;password_hash/password_hash_library&#39;</span><span class="p">,</span> <span class="s1">&#39;pass&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
        <span class="c1">// Load users data</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;simple_auth_users_data&#39;</span><span class="p">,</span> <span class="s1">&#39;u&#39;</span><span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">u</span><span class="o">-&gt;</span><span class="na">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">);</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span> <span class="o">!==</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">pass</span><span class="o">-&gt;</span><span class="na">check_password</span><span class="p">(</span><span class="nv">$password</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;hash_pass&#39;</span><span class="p">]))</span> <span class="p">{</span>	
                <span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
            <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
                <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
            <span class="p">}</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
            <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Sets the restricted URI requested by the user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  string  $uri  The URI to save as the requested URI</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_set_requested_uri</span><span class="p">(</span><span class="nv">$uri</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;requested_uri&#39;</span><span class="p">,</span> <span class="nv">$uri</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Returns the URI requested before the user was redirected to the login page</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  boolean $clear        If the requested url should be cleared from the session after it is retrieved</span> 
<span class="sd">     * @param  string  $default_uri  The default URI to return if the user was not redirected</span> 
<span class="sd">     * @return string  The URI that was requested before they were redirected to the login page</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_requested_uri</span><span class="p">(</span><span class="nv">$clear</span><span class="p">,</span> <span class="nv">$default_uri</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$requested_uri</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;requested_uri&#39;</span><span class="p">,</span> <span class="nv">$default_uri</span><span class="p">);</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nv">$clear</span><span class="p">)</span> <span class="p">{</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">delete</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span> <span class="s1">&#39;requested_uri&#39;</span><span class="p">);</span> 
        <span class="p">}</span> 
        <span class="k">return</span> <span class="nv">$requested_uri</span><span class="p">;</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Sets some piece of information to use to identify the current user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  mixed $token  The user&#39;s token. This could be a user id, an email address, a user object, etc.</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_set_user_token</span><span class="p">(</span><span class="nv">$token</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_token&#39;</span><span class="p">,</span> <span class="nv">$token</span><span class="p">);</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">regenerate_id</span><span class="p">();</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Gets the value that was set as the user token, `NULL` if no token has been set</span> 
<span class="sd">     * </span> 
<span class="sd">     * @return mixed  The user token that had been set, `NULL` if none</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_user_token</span><span class="p">()</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_token&#39;</span><span class="p">,</span> <span class="k">NULL</span><span class="p">);</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/managers/simple_auth_manager.php */</span> 
</pre></div>

<div class="notebox">
Please check out the <a href="<?php echo APP_URI_BASE; ?>documentation/library/password_hashing/">Password Hashing Library</a> for information on hashing passwords.
</div>

<p>
In this tutorial, we just hardcoded the users data in Users_Data.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Simple_Auth_Users_Data</span> <span class="k">extends</span> <span class="nx">Data</span> <span class="p">{</span> 
	<span class="sd">/**</span> 
<span class="sd">     * Sample user data</span> 
<span class="sd">     * You can also get the user data from database</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var array</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="nv">$_users</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;id&#39;</span><span class="o">=&gt;</span><span class="m">1</span><span class="p">,</span> 
            <span class="s1">&#39;fullname&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;Zhou (Joe) Yuan&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;username&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;zhouy&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;hash_pass&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;$P$BN7rmT7I1KKipgOKsefSTOUL6QtIBE1&#39;</span><span class="p">,</span> 
        <span class="p">),</span> 
        <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;id&#39;</span><span class="o">=&gt;</span><span class="m">2</span><span class="p">,</span> 
            <span class="s1">&#39;fullname&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;Fran Mossberg&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;username&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;franm&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;hash_pass&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;$P$BC51a5TOFCeTZkh7oD/tDVAgxhYquI/&#39;</span><span class="p">,</span> 
        <span class="p">),</span> 
    <span class="p">);</span> 
	
 
    <span class="sd">/**</span> 
<span class="sd">     * Get user&#39;s info based on username provided</span> 
<span class="sd">     *</span> 
<span class="sd">     * @return mixed NULL if no match or array if found</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$user</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">;</span> 
        <span class="nv">$cnt</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">);</span> 
        <span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="m">0</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;</span> <span class="nv">$cnt</span><span class="p">;</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">[</span><span class="nv">$i</span><span class="p">][</span><span class="s1">&#39;username&#39;</span><span class="p">]</span> <span class="o">===</span> <span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
                <span class="nv">$user</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">[</span><span class="nv">$i</span><span class="p">];</span> 
                <span class="k">break</span><span class="p">;</span> 
            <span class="p">}</span> 
        <span class="p">}</span> 
        <span class="k">return</span> <span class="nv">$user</span><span class="p">;</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/data/simple_auth_users_data.php */</span> 
</pre></div>

<p>
Take the user for example, after successfully logged in, the user will be redirected to the Home page, then we need to check if the user is already logged in. If by some reason, the user clicks the "Logout" button, then when he wants to come back to the Home page.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Manager</span> <span class="k">extends</span> <span class="nx">Simple_Auth_Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// A call to parent::__construct() within the child constructor is required</span> 
	<span class="c1">// to make the $this-&gt;_user_fullname available</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_require_auth</span><span class="p">();</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Home&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;user_fullname&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/home&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/default&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/home_manager.php</span> 
</pre></div> 

<p>
By using a constructor to check the auth for login required managers we can achieve manager-level Authorization. While sometimes it's possible that we want to allow access to some manager methods without the user being logged in (such as a user registration). In the following example, only access to index requires login auth and the news method is accessible without login.
</p>

<div class="syntax">
<pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Manager</span> <span class="k">extends</span> <span class="nx">Simple_Auth_Manager</span> <span class="p">{</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Only this method requires login auth</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_require_auth</span><span class="p">();</span> 
 
        <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Home&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;user_fullname&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/home&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/default&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_registration</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code </span> 
    <span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/home_manager.php </span> 
</pre></div>

<h2>Authorization Levels</h2>

<p>
Each authorization level is assigned a number. If a user has a number that is the same or above the required level, they can view a page. If not, they will be redirected to the login page.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Level_Auth_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="c1">// Define session prefix</span> 
    <span class="k">const</span> <span class="no">SESSION_KEY</span> <span class="o">=</span> <span class="s1">&#39;user::&#39;</span><span class="p">;</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * The valid authorization levels</span> 
<span class="sd">     * </span> 
<span class="sd">     * @var array</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="nv">$_levels</span><span class="p">;</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * The logged in user fullname to be displayed in layout template</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var string</span> 
<span class="sd">     */</span> 
    <span class="k">protected</span> <span class="nv">$_user_fullname</span><span class="p">;</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * User data</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var array</span> 
<span class="sd">     */</span> 
     <span class="k">private</span> <span class="nv">$_user</span><span class="p">;</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Constructor</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// A call to parent::__construct() within the child constructor is required</span> 
        <span class="c1">// to make the $this-&gt;POST_DATA available</span> 
        <span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
		
        <span class="c1">// Define the valid authorization levels</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;admin&#39;</span> <span class="o">=&gt;</span> <span class="m">100</span><span class="p">,</span> 
            <span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="m">50</span><span class="p">,</span> 
            <span class="s1">&#39;guest&#39;</span> <span class="o">=&gt;</span> <span class="m">25</span> 
        <span class="p">);</span> 
	
        <span class="c1">// Get the layout variable</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Checks if a user has the desired auth level</span> 
<span class="sd">     *</span> 
<span class="sd">     * @param  string $level  The level to check against the logged in user&#39;s level</span> 
<span class="sd">     */</span> 
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_require_auth_level</span><span class="p">(</span><span class="nv">$level</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nv">$level</span> <span class="o">===</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">exit</span><span class="p">(</span><span class="s1">&#39;Please specify the required authorization level for this resource.&#39;</span><span class="p">);</span> 
        <span class="p">}</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_check_auth_level</span><span class="p">(</span><span class="nv">$level</span><span class="p">)</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
            <span class="c1">// Remember the URI requested before the user was redirected to the login page</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_set_requested_uri</span><span class="p">(</span><span class="s1">&#39;http://&#39;</span><span class="o">.</span><span class="nv">$_SERVER</span><span class="p">[</span><span class="s1">&#39;SERVER_NAME&#39;</span><span class="p">]</span><span class="o">.</span><span class="nv">$_SERVER</span><span class="p">[</span><span class="s1">&#39;REQUEST_URI&#39;</span><span class="p">]);</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_login</span><span class="p">();</span> 
            <span class="k">exit</span><span class="p">;</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * This method must be declaired as private to make it inaccessible directly from /auth/login/</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_login</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/login&#39;</span><span class="p">),</span> 
        <span class="p">);</span> 
		
        <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/login&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
            <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
        <span class="p">);</span> 
 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * This method must be declaired as public</span> 
<span class="sd">     * so that it&#39;s accessible directly to the login form&#39;s post action - /auth/login/</span> 
<span class="sd">     * Since it&#39;s a POST request, no access directly from the URI</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_login</span><span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$username</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
        <span class="nv">$password</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
        <span class="nv">$auto_login</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;auto_login&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">[</span><span class="s1">&#39;auto_login&#39;</span><span class="p">]</span> <span class="o">:</span> <span class="m">0</span><span class="p">;</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_identify</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="o">===</span> <span class="k">TRUE</span><span class="p">)</span> <span class="p">{</span> 
            <span class="c1">// Store some user data in Session</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;uid&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
			
            <span class="c1">// Set the user fullname of logged in user to be used in layout template</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;fullname&#39;</span><span class="p">]);</span> 
 
            <span class="c1">// Set the user token</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_set_user_token</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
			
            <span class="c1">// Set the user&#39;s auth level</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_set_user_auth_level</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;auth_level&#39;</span><span class="p">]);</span> 
            
            <span class="c1">// Keep me logged in</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$auto_login</span> <span class="o">===</span> <span class="s1">&#39;1&#39;</span><span class="p">)</span> <span class="p">{</span> 
                <span class="nx">Session</span><span class="o">::</span><span class="na">enable_persistence</span><span class="p">();</span> 
            <span class="p">}</span> 
 
            <span class="c1">// Sometimes when a user visits the login page, they will have entered the URI manually, </span> 
            <span class="c1">// or will have followed a link. In this sort of situation you will need a default page </span> 
            <span class="c1">// to redirect them to. The rest of the time users will usually get directed to the login page </span> 
            <span class="c1">// because they tried to access a restricted page.</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;redirect/redirect_function&#39;</span><span class="p">);</span> 
            <span class="nx">redirect_function</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_requested_uri</span><span class="p">(</span><span class="k">TRUE</span><span class="p">,</span> <span class="nx">APP_URI_BASE</span><span class="o">.</span><span class="s1">&#39;agreement/&#39;</span><span class="p">));</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
            <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;auth&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span><span class="p">,</span> 
            <span class="p">);</span> 
			
            <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
                <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/login&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
            <span class="p">);</span> 
			
            <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
                <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/login&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
                <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
            <span class="p">);</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
        <span class="p">}</span> 
		
		
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Logout and destroy user&#39;s session data</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_logout</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Clear stored user session data</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">clear</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="p">);</span> 
 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_function</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;redirect/redirect_function&#39;</span><span class="p">);</span> 
        <span class="nx">redirect_function</span><span class="p">(</span><span class="nx">APP_URI_BASE</span><span class="o">.</span><span class="s1">&#39;home/&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Identifies a user based on username/password</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_identify</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;iteration_count_log2&#39;</span> <span class="o">=&gt;</span> <span class="m">8</span><span class="p">,</span> 
            <span class="s1">&#39;portable_hashes&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span> 
        <span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;password_hash/password_hash_library&#39;</span><span class="p">,</span> <span class="s1">&#39;pass&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
        <span class="c1">// Load users data</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;level_auth_users_data&#39;</span><span class="p">,</span> <span class="s1">&#39;u&#39;</span><span class="p">);</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">u</span><span class="o">-&gt;</span><span class="na">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">);</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span> <span class="o">!==</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">pass</span><span class="o">-&gt;</span><span class="na">check_password</span><span class="p">(</span><span class="nv">$password</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user</span><span class="p">[</span><span class="s1">&#39;hash_pass&#39;</span><span class="p">]))</span> <span class="p">{</span>	
                <span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
            <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
                <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
            <span class="p">}</span> 
        <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
            <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Sets the restricted URI requested by the user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  string  $uri  The URI to save as the requested URI</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_set_requested_uri</span><span class="p">(</span><span class="nv">$uri</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;requested_uri&#39;</span><span class="p">,</span> <span class="nv">$uri</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Returns the URI requested before the user was redirected to the login page</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  boolean $clear        If the requested url should be cleared from the session after it is retrieved</span> 
<span class="sd">     * @param  string  $default_uri  The default URI to return if the user was not redirected</span> 
<span class="sd">     * @return string  The URI that was requested before they were redirected to the login page</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_requested_uri</span><span class="p">(</span><span class="nv">$clear</span><span class="p">,</span> <span class="nv">$default_uri</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$requested_uri</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;requested_uri&#39;</span><span class="p">,</span> <span class="nv">$default_uri</span><span class="p">);</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nv">$clear</span><span class="p">)</span> <span class="p">{</span> 
            <span class="nx">Session</span><span class="o">::</span><span class="na">delete</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span> <span class="s1">&#39;requested_uri&#39;</span><span class="p">);</span> 
        <span class="p">}</span> 
        <span class="k">return</span> <span class="nv">$requested_uri</span><span class="p">;</span> 
    <span class="p">}</span> 
 
    <span class="sd">/**</span> 
<span class="sd">     * Sets some piece of information to use to identify the current user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  mixed $token  The user&#39;s token. This could be a user id, an email address, a user object, etc.</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_set_user_token</span><span class="p">(</span><span class="nv">$token</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_token&#39;</span><span class="p">,</span> <span class="nv">$token</span><span class="p">);</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">regenerate_id</span><span class="p">();</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Gets the value that was set as the user token, `NULL` if no token has been set</span> 
<span class="sd">     * </span> 
<span class="sd">     * @return mixed  The user token that had been set, `NULL` if none</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_user_token</span><span class="p">()</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_token&#39;</span><span class="p">,</span> <span class="k">NULL</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Checks to see if the logged in user has the specified auth level</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  string $level  The level to check against the logged in user&#39;s level</span> 
<span class="sd">     * @return boolean  If the user has the required auth level</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_check_auth_level</span><span class="p">(</span><span class="nv">$level</span><span class="p">)</span> <span class="p">{</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_user_auth_level</span><span class="p">())</span> <span class="p">{</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_validate_auth_level</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_user_auth_level</span><span class="p">());</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_validate_auth_level</span><span class="p">(</span><span class="nv">$level</span><span class="p">);</span> 
			
            <span class="nv">$user_auth_level_number</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span><span class="p">[</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_get_user_auth_level</span><span class="p">()];</span> 
            <span class="nv">$required_auth_level_number</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span><span class="p">[</span><span class="nv">$level</span><span class="p">];</span> 
			
            <span class="k">if</span> <span class="p">(</span><span class="nv">$user_auth_level_number</span> <span class="o">&gt;=</span> <span class="nv">$required_auth_level_number</span><span class="p">)</span> <span class="p">{</span> 
                <span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
            <span class="p">}</span> 
        <span class="p">}</span> 
		
        <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Gets the authorization level for the logged in user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @return string  The logged in user&#39;s auth level</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_get_user_auth_level</span><span class="p">()</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_auth_level&#39;</span><span class="p">,</span> <span class="k">NULL</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Sets the authorization level for the logged in user</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  string $level  The logged in user&#39;s auth level</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_set_user_auth_level</span><span class="p">(</span><span class="nv">$level</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_validate_auth_level</span><span class="p">(</span><span class="nv">$level</span><span class="p">);</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;user_auth_level&#39;</span><span class="p">,</span> <span class="nv">$level</span><span class="p">);</span> 
        <span class="nx">Session</span><span class="o">::</span><span class="na">regenerate_id</span><span class="p">();</span> 
    <span class="p">}</span> 
	
    <span class="sd">/**</span> 
<span class="sd">     * Makes sure auth levels have been set, and that the specified auth level is valid</span> 
<span class="sd">     * </span> 
<span class="sd">     * @param  string $level  The level to validate</span> 
<span class="sd">     * @return void</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_validate_auth_level</span><span class="p">(</span><span class="nv">$level</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span> <span class="o">===</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">echo</span> <span class="s1">&#39;No authorization levels have been set&#39;</span><span class="p">;</span> 
        <span class="p">}</span> 
		
        <span class="k">if</span> <span class="p">(</span><span class="nv">$level</span> <span class="o">!==</span> <span class="k">NULL</span> <span class="o">&amp;&amp;</span> <span class="o">!</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span><span class="p">[</span><span class="nv">$level</span><span class="p">]))</span> <span class="p">{</span> 
            <span class="nb">printf</span><span class="p">(</span> 
                <span class="s1">&#39;The authorization level specified, %1$s, is invalid. Must be one of: %2$s.&#39;</span><span class="p">,</span> 
                <span class="nv">$level</span><span class="p">,</span> 
                <span class="nb">join</span><span class="p">(</span><span class="s1">&#39;, &#39;</span><span class="p">,</span> <span class="nb">array_keys</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_levels</span><span class="p">))</span> 
            <span class="p">);</span> 
        <span class="p">}</span> 
    <span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/managers/level_auth_manager.php */</span> 
</pre></div>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Level_Auth_Users_Data</span> <span class="k">extends</span> <span class="nx">Data</span> <span class="p">{</span> 
    <span class="sd">/**</span> 
<span class="sd">     * Sample user data</span> 
<span class="sd">     * You can also get the user data from database</span> 
<span class="sd">     *</span> 
<span class="sd">     * @var array</span> 
<span class="sd">     */</span> 
    <span class="k">private</span> <span class="nv">$_users</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
        <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;id&#39;</span><span class="o">=&gt;</span><span class="m">1</span><span class="p">,</span> 
            <span class="s1">&#39;fullname&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;Zhou (Joe) Yuan&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;username&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;zhouy&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;hash_pass&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;$P$BN7rmT7I1KKipgOKsefSTOUL6QtIBE1&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;auth_level&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;admin&#39;</span> 
        <span class="p">),</span> 
        <span class="k">array</span><span class="p">(</span> 
            <span class="s1">&#39;id&#39;</span><span class="o">=&gt;</span><span class="m">2</span><span class="p">,</span> 
            <span class="s1">&#39;fullname&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;Fran Mossberg&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;username&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;franm&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;hash_pass&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;$P$BC51a5TOFCeTZkh7oD/tDVAgxhYquI/&#39;</span><span class="p">,</span> 
            <span class="s1">&#39;auth_level&#39;</span><span class="o">=&gt;</span><span class="s1">&#39;user&#39;</span> 
        <span class="p">),</span> 
    <span class="p">);</span> 
	
 
    <span class="sd">/**</span> 
<span class="sd">     * Get user&#39;s info based on username provided</span> 
<span class="sd">     *</span> 
<span class="sd">     * @return mixed NULL if no match or array if found</span> 
<span class="sd">     */</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
        <span class="nv">$user</span> <span class="o">=</span> <span class="k">NULL</span><span class="p">;</span> 
        <span class="nv">$cnt</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">);</span> 
        <span class="k">for</span> <span class="p">(</span><span class="nv">$i</span> <span class="o">=</span> <span class="m">0</span><span class="p">;</span> <span class="nv">$i</span> <span class="o">&lt;</span> <span class="nv">$cnt</span><span class="p">;</span> <span class="nv">$i</span><span class="o">++</span><span class="p">)</span> <span class="p">{</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">[</span><span class="nv">$i</span><span class="p">][</span><span class="s1">&#39;username&#39;</span><span class="p">]</span> <span class="o">===</span> <span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
                <span class="nv">$user</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_users</span><span class="p">[</span><span class="nv">$i</span><span class="p">];</span> 
                <span class="k">break</span><span class="p">;</span> 
            <span class="p">}</span> 
        <span class="p">}</span> 
        <span class="k">return</span> <span class="nv">$user</span><span class="p">;</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/data/level_auth_users_data.php */</span> 
</pre></div>

<p>
You'll notice that the only difference is that each user has a 'auth_level' field this time. And in your manager, you need to extend Level_Auth_Manager. Besides, you will need to use <strong>_require_auth_level('user')</strong> to specify the required level.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Manager</span> <span class="k">extends</span> <span class="nx">Level_Auth_Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// A call to parent::__construct() within the child constructor is required </span> 
	<span class="c1">// to make the $this-&gt;_user_fullname available </span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_require_auth_level</span><span class="p">(</span><span class="s1">&#39;user&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Home&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;user_fullname&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/home&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/default&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/home_manager.php </span> 
</pre></div>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Home_Manager</span> <span class="k">extends</span> <span class="nx">Level_Auth_Manager</span> <span class="p">{</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Only this method requires login auth </span> 
        <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_require_auth_level</span><span class="p">(</span><span class="s1">&#39;user&#39;</span><span class="p">);</span> 
 
        <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Home&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;user_fullname&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_user_fullname</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;pages/home&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;layouts/default&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_registration</span><span class="p">()</span> <span class="p">{</span> 
        <span class="c1">// Some code  </span> 
    <span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/home_manager.php  </span> 
</pre></div>

<!-- PRINT: stop -->

</div> 
<!-- end onecolumn -->
