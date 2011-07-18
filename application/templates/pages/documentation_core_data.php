<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Data Access Object</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Data Access Object
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/data/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Data Access Objects (DAO) provides a generic API to access data stored in different database management systems (DBMS). As a result, the underlying DBMS can be changed to a different one without requiring change of the code which uses DAO to access the data.
</p>

<p>
Data Access Objects are responsible for any logic that surrounds the manipulation of a data entity. Its typical responsibilities are to faciliate create, retrieve, update and delete (CRUD) operations. If more advanced manipulation/processing functions are required for an entity, they will also be located here. Data is <strong>optional</strong> in an InfoPotato application. For many RDBMS-based web applications, data usually represents the access to database tables, but they could also represent Non-SQL databases, like LDAP entries, XML data, or document-oriented database, like <a href="http://www.mongodb.org/" class="external_link">MongoDB</a>.
</p> 

<p>
In an InfoPotato application, the use of data layer is not required if there is no data access involved. And a data object is not allowed to be associated with other data objects. A data file can be loaded and used by different managers which need the data access that this data provides.
</p>

<div class="notebox">
One of the ideas of InfoPotato is that it allows you to choose your own database toolkit, you're not forced to use what we think is best.
</div>

<h2>What is a Data?</h2> 
 
<p>A data is an instance of the base Data object or other data objects' child class. Data object is used to keep data and their relevant business rules. For example, let's say you use InfoPotato to manage a blog. You might have a data class that contains functions to insert, update, and retrieve your blog post data. Here is an example of what such a data class might look like:</p> 
 
<div class="syntax">
<pre>
<span class="k">class</span> <span class="nc">Users_Data</span> <span class="k">extends</span> <span class="nx">Data</span> <span class="p">{</span> 
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
</pre>
</div> 
 
<p>
Create your data object file in the /application/data/ directory or in a subdirectory of /application/data. InfoPotato will find it anywhere in the directory. By convention it should have the same name as the class.
</p> 
 
<p>
For example, we created a data and put it in <span class="red">application/data/users_data.php</span>, and the the data class name will be <span class="red">Users_Data</span>.
</p> 

<h2>Naming Data Objects</h2>
<ul>
<li>All Data Object Files go into the application/data/ directory. This directory can be defined using APP_DATA_DIR</li>
<li>Data filenames are lowercase, have _data appended to them and should be the singular form of the name.</li>
<li>The Data class name is capitalized, must have _Data appended to it and should be the singular form of the name.</li>
<li>Sub folder is supported, e.g., you can have application/data/blog/post_data.php</li>
</ul>

<h2>Using other datas from within a data</h2>

<p>
It's possible for a data object to depend on other data objects. This occurs a lot when you have entities that link to each other. A data for managing users probably shouldn't directly modify another entity type, so instead it can ask the respective data to do the work on its behalf.
</p>
 
<p class="tipbox">
In InfoPotato, using other data objects from within a data object is <strong>NOT ALLOWED</strong>.
</p>

<h2>Loading a Data in Manager</h2> 
 
<p>Your datas will typically be loaded and called from within your manager functions. To load a data you will use the following function:</p> 
 
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
 
<p>If your data is located in a sub-folder, include the relative path from your datas folder.  For example, if you have a data located at <dfn>application/datas/blog/users_data.php</dfn> you'll load it using:</p> 
 
 
<p>Once loaded, you will access your data functions using an object with the same name as your class.</p> 
 
<p>Here is an example of a controller, that loads a data, then serves a view:</p> 
 
<div class="syntax">
<pre>
<span class="k">private</span> <span class="k">function</span> <span class="nf">_all</span><span class="p">(</span><span class="nv">$params</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
    <span class="nv">$current_page</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$params</span><span class="p">)</span> <span class="o">&gt;</span> <span class="m">0</span> <span class="o">?</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">]</span> <span class="o">:</span> <span class="m">1</span><span class="p">;</span> 
	
    <span class="c1">// Load data</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;admin_data&#39;</span><span class="p">,</span> <span class="s1">&#39;admin&#39;</span><span class="p">);</span> 
	
    <span class="c1">// Load Pagination library</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;pagination/pagination_library&#39;</span><span class="p">,</span> <span class="s1">&#39;page&#39;</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">items_per_page</span> <span class="o">=</span> <span class="m">30</span><span class="p">;</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">mid_range</span> <span class="o">=</span> <span class="m">7</span><span class="p">;</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">current_page</span> <span class="o">=</span> <span class="nv">$current_page</span><span class="p">;</span>	
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">items_total</span> <span class="o">=</span> <span class="nb">count</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">admin</span><span class="o">-&gt;</span><span class="na">get_all_requests</span><span class="p">());</span>	
    <span class="nv">$pagination_data</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">page</span><span class="o">-&gt;</span><span class="na">build_pagination</span><span class="p">();</span> 
	
    <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;requests&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">admin</span><span class="o">-&gt;</span><span class="na">get_all_requests</span><span class="p">(),</span> 
	<span class="s1">&#39;page_data&#39;</span> <span class="o">=&gt;</span> <span class="nv">$pagination_data</span><span class="p">,</span> 
    <span class="p">);</span> 
 
    <span class="nv">$layout_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;page_title&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;All Requests&#39;</span><span class="p">,</span> 
	<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">fetch</span><span class="p">(</span><span class="s1">&#39;pages/admin_all_content_view&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
    <span class="p">);</span> 
	
    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">fetch</span><span class="p">(</span><span class="s1">&#39;layouts/admin_layout_view&#39;</span><span class="p">,</span> <span class="nv">$layout_data</span><span class="p">),</span> 
    <span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">view</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span>	
<span class="p">}</span> 
</pre></div>  
 
<h2>Connecting to your Database</h2> 
 
<p>
If RDBMS used in your application, you can define the database connection in application/configs/database.php
</p> 
 
<div class="syntax"><pre>
<span class="k">return</span> <span class="k">array</span><span class="p">(</span> 
	<span class="c1">// MySQL</span> 
	<span class="s1">&#39;mysql_adapter&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
			<span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server.</span> 
			<span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to</span> 
			<span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database</span> 
			<span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database</span> 
			<span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database</span> 
			<span class="s1">&#39;collate&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8_general_ci&#39;</span><span class="p">,</span> <span class="c1">//  The Database Collate type. Don&#39;t change this if in doubt.</span> 
		<span class="p">),</span> 
	<span class="p">),</span> 
	
	<span class="c1">// MySQLi</span> 
	<span class="s1">&#39;mysqli_adapter&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
			<span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server.</span> 
			<span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to</span> 
			<span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database</span> 
			<span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database</span> 
			<span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database</span> 
			<span class="s1">&#39;collate&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8_general_ci&#39;</span><span class="p">,</span> <span class="c1">//  The Database Collate type. Don&#39;t change this if in doubt.</span> 
		<span class="p">),</span> 
	<span class="p">),</span> 
	
	<span class="c1">// PostgreSQL</span> 
	<span class="s1">&#39;postgresql_adapter&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
			<span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server.</span> 
			<span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to</span> 
			<span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database</span> 
			<span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database</span> 
			<span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;UTF8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database</span> 
		<span class="p">),</span> 
	<span class="p">),</span> 
	
	<span class="c1">// SQLite</span> 
	<span class="s1">&#39;sqlite_adapter&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;test&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
			<span class="s1">&#39;path&#39;</span> <span class="o">=&gt;</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;test.sqlite&#39;</span><span class="p">,</span> 
		<span class="p">),</span> 
	<span class="p">),</span> 
<span class="p">);</span> 
</pre></div> 
 
<h2>Storing Datas within Sub-folders</h2> 
<p>Your data files can also be stored within sub-folders if you prefer that type of organization.  When doing so you will need to include the folder name loading the data.  Example:</p> 
 
<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">User_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_user_post</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load post data, this data file is stored under /datas/post/ folder</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_data</span><span class="p">(</span><span class="s1">&#39;post/post_data&#39;</span><span class="p">,</span> <span class="s1">&#39;p&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
