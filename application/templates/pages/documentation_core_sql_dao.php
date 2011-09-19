<div class="container"> 

<div class="row">
	
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
<p>
Data Access Objects (DAO) provides a generic API to access data stored in different database management systems (DBMS). Currently, there are database access objects for MySQL, MySQLi, SQLite, and PostgreSQL. Each DAO class is loosely based on the <a href="http://justinvincent.com/ezsql" class="external_link" title="http://justinvincent.com/ezsql">ezSQL</a> class written and maintained by <a href="http://www.jvmultimedia.com" class="external_link" title="http://www.jvmultimedia.com">Justin Vincent</a>. Wordpress's database access is also built on top of ezSQL.
</p>

<h2>Overview</h2>
<ul>
<li>It allows you to cache query results and manipulate and extract them without causing extra server overhead</li>
<li>It has excellent debug functions making it lightning-fast to see what’s going on in your SQL code</li>
<li>Most DAO functions can return results as Objects (OBJECT), Associative Arrays (ARRAY_A), or Numerical Arrays (ARRAY_N)</li>
</ul>

<div class="notebox">
If you use MySQL as your database server, DON'T mix use MySQL_DAO and MySQLi_DAO.
</div>

<h2>Example Database</h2>

<p>
To demonstrate the useage of InfoPotato's DAO we will use <tt><a href="http://dev.mysql.com/doc/index-other.html" class="external_link">world</a></tt> example database provided by MySQL.
</p>

<p>
There are three tables in the <tt>world</tt> database:
</p>

<ul>
<li>
<tt>Country</tt>: Information about countries of the world.
</li>

<li>
<tt>City</tt>: Information about some of the cities in those countries.
</li>

<li>
<tt>Countrylanguage</tt>: Languages spoken in each country.
</li>
<ul>

<h2>Quick Examples</h2>

<h3>$this->db->get_row() - Get one row from the database</h3>

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
  
<h3>$this->db->get_results() - Get multiple row result set from the database</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_districts</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="s2">&quot;SELECT * FROM districts&quot;</span><span class="p">;</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$districts</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_results</span><span class="p">(</span><span class="nv">$sql</span><span class="p">,</span> <span class="nx">ARRAY_A</span><span class="p">))</span> <span class="p">{</span> 
        <span class="k">return</span> <span class="nv">$districts</span><span class="p">;</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div> 

<h3>$this->db->query() - Send a query to the database</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">add_record</span><span class="p">(</span><span class="nv">$uid</span><span class="p">,</span> <span class="nv">$date</span><span class="p">,</span> <span class="nv">$type</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$return_val</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span>	
    <span class="c1">// Database Transaction </span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">trans_begin</span><span class="p">();</span>  
 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;INSERT INTO records (uid, date, type)  </span> 
<span class="s2">			       VALUES (?, ?, ?)&quot;</span><span class="p">,</span> 
			       <span class="k">array</span><span class="p">(</span><span class="nv">$uid</span><span class="p">,</span> <span class="nv">$date</span><span class="p">,</span> <span class="nv">$type</span><span class="p">));</span> 
    <span class="k">if</span> <span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">query</span><span class="p">(</span><span class="nv">$sql</span><span class="p">)</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
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

<h3>$this->db->get_var() - Gets one single variable from the database or previously cached results</h3>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">count_requests</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$sql</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">prepare</span><span class="p">(</span><span class="s2">&quot;SELECT count(*) FROM requests&quot;</span><span class="p">);</span> 
    <span class="k">return</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">db</span><span class="o">-&gt;</span><span class="na">get_var</span><span class="p">(</span><span class="nv">$sql</span><span class="p">);</span> 
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

</div> 

</div>
