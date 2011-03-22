<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; <a href="<?php echo BASE_URI; ?>tutorials/">Tutorials</a> &gt; Authentication and Authorization
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Authentication and Authorization</h1>	

<p>
Authentication and authorization are required for a Web page that should be limited to certain users. Authentication is about verifying whether someone is who they claim to be. It usually involves a username and a password, but may include any other methods of demonstrating identity, such as a smart card, fingerprints, etc. Authorization is finding out if the person, once identified (i.e. authenticated), is permitted to manipulate specific resources. This is usually determined by finding out if that person is of a particular role that has access to the resources.
</p>

<h2>Authentication View</h2>

<p>
As mentioned above, authentication is about validating the identity of the user. A typical Web application authentication implementation usually involves using a username and password combination to verify a user's identity.
</p>

<div class="syntax"><pre><span class="nt">&lt;h1&gt;</span>Login<span class="nt">&lt;/h1&gt;</span> 
 
<span class="nt">&lt;form</span> <span class="na">name=</span><span class="s">&quot;login_form&quot;</span> <span class="na">method=</span><span class="s">&quot;post&quot;</span> <span class="na">action=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">BASE_URI</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">auth/submit_login/&quot;</span><span class="nt">&gt;</span>  
 
<span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$auth</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="nv">$auth</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="nt">&lt;h2</span> <span class="na">class=</span><span class="s">&quot;center&quot;</span><span class="nt">&gt;</span>Incorrect Username/Password Combination<span class="nt">&lt;/h2&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
 
Username: <span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;text&quot;</span> <span class="na">name=</span><span class="s">&quot;username&quot;</span> <span class="na">size=</span><span class="s">&quot;30&quot;</span> <span class="na">maxlength=</span><span class="s">&quot;50&quot;</span> <span class="na">value=</span><span class="s">&quot;&quot;</span> <span class="nt">/&gt;</span>  
 
Password: <span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;password&quot;</span> <span class="na">name=</span><span class="s">&quot;password&quot;</span> <span class="na">size=</span><span class="s">&quot;30&quot;</span> <span class="na">maxlength=</span><span class="s">&quot;50&quot;</span> <span class="na">value=</span><span class="s">&quot;&quot;</span> <span class="nt">/&gt;</span>  
 
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;submit&quot;</span> <span class="na">name=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">value=</span><span class="s">&quot;Login&quot;</span> <span class="nt">/&gt;</span>  
 
<span class="nt">&lt;/form&gt;</span>  
</pre></div> 

<h2>Authentication Worker</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Auth_Worker</span> <span class="k">extends</span> <span class="nx">Worker</span> <span class="p">{</span> 
    <span class="c1">// Define session prefix</span> 
    <span class="k">const</span> <span class="no">USER_SESSION_KEY</span> <span class="o">=</span> <span class="s1">&#39;user::&#39;</span><span class="p">;</span> 
    <span class="k">const</span> <span class="no">ADMIN_SESSION_KEY</span> <span class="o">=</span> <span class="s1">&#39;admin::&#39;</span><span class="p">;</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">();</span> 
	<span class="c1">// Load session class</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;session/session_library&#39;</span><span class="p">,</span> <span class="s1">&#39;s&#39;</span><span class="p">);</span> 
	<span class="c1">// APP_SESSION_DIR is defined in index.php</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set_path</span><span class="p">(</span><span class="nx">APP_SESSION_DIR</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_check_auth</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">))</span> <span class="p">{</span> 
		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">assign_template_global</span><span class="p">(</span><span class="s1">&#39;user_fullname&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">));</span> 
		<span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
	<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
		<span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
	<span class="p">}</span>	
    <span class="p">}</span> 
	
    <span class="k">protected</span> <span class="k">function</span> <span class="nf">_check_admin_auth</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">))</span> <span class="p">{</span> 
		<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">assign_global</span><span class="p">(</span><span class="s1">&#39;admin_fullname&#39;</span><span class="p">,</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">));</span> 
		<span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
	<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
		<span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
	<span class="p">}</span>	
    <span class="p">}</span> 
	
    <span class="k">private</span> <span class="k">function</span> <span class="nf">_login_auth</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;iteration_count_log2&#39;</span> <span class="o">=&gt;</span> <span class="m">8</span><span class="p">,</span> 
		<span class="s1">&#39;portable_hashes&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;password_hash/password_hash_library&#39;</span><span class="p">,</span> <span class="s1">&#39;pass&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
		
	<span class="c1">// Load users data and user info from database</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;users_data&#39;</span><span class="p">,</span> <span class="s1">&#39;users&#39;</span><span class="p">);</span> 
	<span class="nv">$user</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">users</span><span class="o">-&gt;</span><span class="na">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">);</span> 
		
	<span class="k">if</span> <span class="p">(</span><span class="nv">$user</span> <span class="o">!=</span> <span class="k">NULL</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$hash_pass</span><span class="o">=</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;hash_pass&#39;</span><span class="p">];</span> 
            <span class="nv">$user_role</span> <span class="o">=</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;role&#39;</span><span class="p">];</span> 
	    <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">pass</span><span class="o">-&gt;</span><span class="na">check_password</span><span class="p">(</span><span class="nv">$password</span><span class="p">,</span> <span class="nv">$hash_pass</span><span class="p">))</span> <span class="p">{</span>	
		<span class="k">if</span> <span class="p">(</span><span class="nv">$user_role</span> <span class="o">==</span> <span class="s1">&#39;admin&#39;</span><span class="p">)</span> <span class="p">{</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;uid&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;fullname&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;username&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;email&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;email&#39;</span><span class="p">]);</span> 
		<span class="p">}</span> 
		<span class="k">if</span> <span class="p">(</span><span class="nv">$user_role</span> <span class="o">==</span> <span class="s1">&#39;fellow&#39;</span> <span class="k">OR</span> <span class="nv">$user_role</span> <span class="o">==</span> <span class="s1">&#39;staff&#39;</span><span class="p">)</span> <span class="p">{</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;uid&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;id&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;fullname&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;username&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">]);</span> 
		    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">set</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">USER_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;email&#39;</span><span class="p">,</span> <span class="nv">$user</span><span class="p">[</span><span class="s1">&#39;email&#39;</span><span class="p">]);</span> 
		<span class="p">}</span> 
 
		<span class="k">return</span> <span class="k">TRUE</span><span class="p">;</span> 
	    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
		<span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
	    <span class="p">}</span> 
	<span class="p">}</span><span class="k">else</span> <span class="p">{</span> 
	    <span class="k">return</span> <span class="k">FALSE</span><span class="p">;</span> 
	<span class="p">}</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">login</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;pages/login_content_view&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;layouts/login_layout_view&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="s1">&#39;minify_html&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span><span class="p">,</span> 
	<span class="p">);</span> 
 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">submit_login</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$username</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$_POST</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$_POST</span><span class="p">[</span><span class="s1">&#39;username&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
	<span class="nv">$password</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$_POST</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">?</span> <span class="nb">trim</span><span class="p">(</span><span class="nv">$_POST</span><span class="p">[</span><span class="s1">&#39;password&#39;</span><span class="p">])</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_login_auth</span><span class="p">(</span><span class="nv">$username</span><span class="p">,</span> <span class="nv">$password</span><span class="p">)</span> <span class="o">===</span> <span class="k">TRUE</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$base_uri</span> <span class="o">=</span> <span class="nx">BASE_URI</span><span class="p">;</span> 
	    <span class="nx">load_script</span><span class="p">(</span><span class="s1">&#39;redirect/redirect_script&#39;</span><span class="p">);</span> 
	    <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">get</span><span class="p">(</span><span class="nx">self</span><span class="o">::</span><span class="na">ADMIN_SESSION_KEY</span><span class="o">.</span><span class="s1">&#39;fullname&#39;</span><span class="p">))</span> <span class="p">{</span> 
		<span class="nx">redirect</span><span class="p">(</span><span class="nv">$base_uri</span><span class="o">.</span><span class="s1">&#39;admin/&#39;</span><span class="p">);</span> 
	    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
		<span class="nx">redirect</span><span class="p">(</span><span class="nv">$base_uri</span><span class="o">.</span><span class="s1">&#39;home/&#39;</span><span class="p">);</span> 
	    <span class="p">}</span> 
	<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	    <span class="c1">// Data to be displayed in Template</span> 
	    <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;auth&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span><span class="p">,</span> 
	    <span class="p">);</span> 
			
	    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;pages/login_content_view&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
	    <span class="p">);</span> 
			
	    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;layouts/login_layout_view&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	    <span class="p">);</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
	<span class="p">}</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">logout</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">s</span><span class="o">-&gt;</span><span class="na">destroy</span><span class="p">();</span> 
		
	<span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Login&#39;</span><span class="p">,</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;pages/login_content_view&#39;</span><span class="p">),</span> 
	<span class="p">);</span> 
		
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;layouts/login_layout_view&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
 
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/controllers/auth_controller.php */</span> 
</pre></div> 

<h2>Authentication Model</h2>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Users_Model</span> <span class="k">extends</span> <span class="nx">Model</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Use default database connection config</span> 
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">(</span><span class="s1">&#39;default&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
	
    <span class="k">public</span> <span class="k">function</span> <span class="nf">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;SELECT * FROM users WHERE username=?&quot;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="nv">$username</span><span class="p">));</span> 
	<span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_row</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">ARRAY_A</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_user_info</span><span class="p">(</span><span class="nv">$id</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;SELECT * FROM users WHERE id=?&quot;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="nv">$id</span><span class="p">));</span> 
	<span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_row</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">ARRAY_A</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
 
<span class="cm">/* End of file: ./application/data/users_data.php */</span> 
</pre></div> 

<h2>Authorization</h2>

<p>
Now we begin to deal with authorization. Take the Administrator for example, after successfully logged in, the administrator will be redirected to the Admin controller, then we need to check if the administrator is already logged in. If by some reason, the administrator clicks the "Logout" button, then when he wants to come back to the Admin page, the system will show the login form again.
</p>

<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">class</span> <span class="nc">Admin_Worker</span> <span class="k">extends</span> <span class="nx">Auth_Worker</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_check_admin_auth</span><span class="p">()</span> <span class="o">===</span> <span class="k">TRUE</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_index</span><span class="p">();</span> 
	<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">login</span><span class="p">();</span> 
	<span class="p">}</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div>

</div> 
<!-- end onecolumn -->
