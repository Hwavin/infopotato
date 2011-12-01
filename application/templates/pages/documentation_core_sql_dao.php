<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">SQL Data Access Object</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; SQL Data Access Object
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/sql_dao/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<h2>Why No ORM In InfoPotato?</h2>

<div class="box_right greybox">
<blockquote>
<span>Any intelligent fool can make things bigger, more complex, and more violent.</span>
<div>&mdash; Albert Einstein</div>
</blockquote>
</div>

<p>
Before we jump in head first, I would like to talk about ORM. Many other PHP frameworks support ORM (e.g., Doctrine), but the most obvious problem with ORM as an abstraction is that it does not adequately abstract away the implementation details. The documentation of all the major ORM libraries is rife with references to SQL concepts. Some introduce them without indicating their equivalents in SQL, while others treat the library as merely a set of procedural functions for generating SQL. InfoPotato aims to be simple and enable you to store and retrive your data in creative ways. The developers are encouraged to write raw SQL queries to work with RDBMS.
</p>

<h2>InfoPotato's Way</h2>

<p>
The decision I made was to not use any type of ORM, SQL generation tool, or ActiveRecord style library. That means all of my SQL is hand written (including INSERT's and UPDATE's) and executed through InfoPotato's Data Access Objects (DAO). DAO provides a generic API to access data stored in different database management systems (DBMS). Currently, there are database access objects for MySQL, MySQLi, SQLite, and PostgreSQL. Each DAO class is loosely based on the <a href="http://justinvincent.com/ezsql" class="external_link" title="http://justinvincent.com/ezsql">ezSQL</a> class written and maintained by <a href="http://www.jvmultimedia.com" class="external_link" title="http://www.jvmultimedia.com">Justin Vincent</a>. Wordpress's database access is also built on top of ezSQL.
</p>

<h2>Overview</h2>
<ul>
<li>It allows you to cache query results and manipulate and extract them without causing extra server overhead</li>
<li>It has excellent debug functions making it lightning-fast to see what’s going on in your SQL code</li>
<li>Most DAO functions can return results as Objects (<span class="red">FETCH_OBJ</span>), Associative Arrays (<span class="red">FETCH_ASSOC</span>), or Numerical Arrays (<span class="red">FETCH_NUM</span>)</li>
</ul>

<div class="notebox">
If you use MySQL as your database server, DON'T mix use MySQL_DAO and MySQLi_DAO.
</div>

<h2>Getting Started</h2>

<p>
Before we execute any database manipulations we need connect to the database first. Luckily this is really easy.
</p>

<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Users_Data</span> <span class="k">extends</span> <span class="nx">Data</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">__construct</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Use default database connection config </span>
	<span class="k">parent</span><span class="o">::</span><span class="na">__construct</span><span class="p">(</span><span class="s1">&#39;mysql_dao:default&#39;</span><span class="p">);</span> 
    <span class="p">}</span> 
<span class="p">}</span>
</pre></div>

<p>
To make the database connection we only need to specify the connection string in the constructor of your data class. For example, <span class="red">mysql_dao:default</span> tells InfoPotato that we are using <span class="red">mysql_dao</span> as our database access object and the <span class="red">default</span> connection config. The database connection is define in application/configs/data_source.php
</p>

<div class="syntax"><pre>
<span class="k">return</span> <span class="k">array</span><span class="p">(</span> 
    <span class="c1">// MySQL </span>
    <span class="s1">&#39;mysql_dao&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server. </span>
	    <span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to </span>
	    <span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database </span>
	    <span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database </span>
	    <span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database </span>
	    <span class="s1">&#39;collate&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8_general_ci&#39;</span><span class="p">,</span> <span class="c1">//  The Database Collate type. Don&#39;t change this if in doubt. </span>
	<span class="p">),</span> 
    <span class="p">),</span> 
	
    <span class="c1">// MySQLi </span>
    <span class="s1">&#39;mysqli_dao&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server. </span>
	    <span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to </span>
	    <span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database </span>
	    <span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database </span>
	    <span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database </span>
	    <span class="s1">&#39;collate&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;utf8_general_ci&#39;</span><span class="p">,</span> <span class="c1">//  The Database Collate type. Don&#39;t change this if in doubt. </span>
	<span class="p">),</span> 
    <span class="p">),</span> 
	
    <span class="c1">// PostgreSQL </span>
    <span class="s1">&#39;postgresql_dao&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;default&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;host&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;localhost&#39;</span><span class="p">,</span> <span class="c1">// The hostname of your database server. </span>
	    <span class="s1">&#39;name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="c1">// The name of the database you want to connect to </span>
	    <span class="s1">&#39;user&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;root&#39;</span><span class="p">,</span> <span class="c1">// The username used to connect to the database </span>
	    <span class="s1">&#39;pass&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;&#39;</span><span class="p">,</span> <span class="c1">// The password used to connect to the database </span>
	    <span class="s1">&#39;charset&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;UTF8&#39;</span><span class="p">,</span> <span class="c1">// The character collation used in communicating with the database </span>
	<span class="p">),</span> 
    <span class="p">),</span> 
	
    <span class="c1">// SQLite </span>
    <span class="s1">&#39;sqlite_dao&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	<span class="s1">&#39;test&#39;</span> <span class="o">=&gt;</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;path&#39;</span> <span class="o">=&gt;</span> <span class="nx">APP_DIR</span><span class="o">.</span><span class="s1">&#39;test.sqlite&#39;</span><span class="p">,</span> 
        <span class="p">),</span> 
    <span class="p">),</span> 
<span class="p">);</span>
</pre></div>

<div class="notebox">
You can define multi connections for each DAO.
</div>

<h2>Quick Examples</h2>

<h3>$this->db->get_row() - Get one row from the database</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">user_exists</span><span class="p">(</span><span class="nv">$username</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;SELECT * FROM users WHERE username=?&quot;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="nv">$username</span><span class="p">));</span> 
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_row</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">FETCH_ASSOC</span><span class="p">);</span> 
<span class="p">}</span> 
 
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_user_info</span><span class="p">(</span><span class="nv">$id</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;SELECT * FROM users WHERE id=?&quot;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="nv">$id</span><span class="p">));</span> 
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_row</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">FETCH_ASSOC</span><span class="p">);</span> 
<span class="p">}</span>
</pre></div> 
  
<h3>$this->db->get_all() - Get multiple row result set from the database</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_districts</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="s2">&quot;SELECT * FROM districts&quot;</span><span class="p">;</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$districts</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_all</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">FETCH_ASSOC</span><span class="p">))</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="nv">$districts</span><span class="p">;</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h3>$this->db->exec_query() - Run Any Query on the Database</h3>

<p>
In situations where no result is required to be iterated over, such as an UPDATE statement, the exec_query() method can be used.
</p>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">add_record</span><span class="p">(</span><span class="nv">$uid</span><span class="p">,</span> <span class="nv">$date</span><span class="p">,</span> <span class="nv">$type</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$return_val</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span>	
    <span class="c1">// Database Transaction </span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">trans_begin</span><span class="p">();</span>  
 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;INSERT INTO records (uid, date, type)  </span> 
<span class="s2">			       VALUES (?, ?, ?)&quot;</span><span class="p">,</span> 
			       <span class="k">array</span><span class="p">(</span><span class="nv">$uid</span><span class="p">,</span> <span class="nv">$date</span><span class="p">,</span> <span class="nv">$type</span><span class="p">));</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">exec_query</span><span class="p">(</span><span class="nv">$sql</span><span class="p">)</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$return_val</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">;</span> 
    <span class="p">}</span> 
 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$return_val</span> <span class="o">==</span> <span class="k">TRUE</span><span class="p">)</span> <span class="p">{</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">trans_commit</span><span class="p">();</span> 
    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">trans_rollback</span><span class="p">();</span> 
    <span class="p">}</span> 
		
    <span class="k">return</span> <span class="nv">$return_val</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div> 

<h3>$this->db->prepare() - Result safe query with escaped user variables</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">add_city</span><span class="p">(</span><span class="nv">$name</span><span class="p">,</span> <span class="nv">$country_code</span><span class="p">,</span> <span class="nv">$district</span><span class="p">,</span> <span class="nv">$population</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;INSERT INTO city(ID, Name, CountryCode, District, Population) </span>
<span class="s2">		               VALUES(?, ?, ?, ?, ?)&quot;</span><span class="p">,</span> 
		               <span class="k">array</span><span class="p">(</span><span class="m">4080</span><span class="p">,</span> <span class="nv">$name</span><span class="p">,</span> <span class="nv">$country_code</span><span class="p">,</span> <span class="nv">$district</span><span class="p">,</span> <span class="nv">$population</span><span class="p">));</span>
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">exec_query</span><span class="p">(</span><span class="nv">$sql</span><span class="p">);</span>
<span class="p">}</span>
</pre></div>

<h3>$this->db->get_cell() - Gets one single variable from the database</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">count_requests</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="s2">&quot;SELECT product FROM requests&quot;</span><span class="p">;</span>  
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_cell</span><span class="p">(</span><span class="nv">$sql</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div> 

<h3>$this->db->get_col() - Extracts one column as one dimensional array based on a column offset</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_product_column</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="s2">&quot;SELECT product FROM products&quot;</span><span class="p">;</span> 
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_col</span><span class="p">(</span><span class="nv">$sql</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>