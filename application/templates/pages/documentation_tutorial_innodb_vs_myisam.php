<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">MySQL Engines: InnoDB vs. MyISAM&mdash;A Comparison of Pros and Cons</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/tutorial/">Tutorials</a> &gt; MySQL Engines: InnoDB vs. MyISAM&mdash;A Comparison of Pros and Cons
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/tutorial/innodb_vs_myisam/'); ?>" class="print">Print</a>

<!-- PRINT: start -->

<p>The 2 major types of table storage engines for MySQL databases are InnoDB and MyISAM. To summarize the differences of features and performance,</p>

<ol>
<li>InnoDB is newer while MyISAM is older.</li>
<li>InnoDB is more <strong>complex</strong> while MyISAM is <strong>simpler</strong>.</li>
<li>InnoDB is more strict in <strong>data integrity</strong> while MyISAM is loose.</li>
<li>InnoDB implements <strong>row-level</strong> lock for inserting and updating while MyISAM implements <strong>table-level</strong> lock.</li>
<li>InnoDB has <strong>transactions</strong> while MyISAM does not.</li>
<li>InnoDB has <strong>foreign keys</strong> and relationship contraints while MyISAM does not.</li>
<li>InnoDB has better crash recovery while MyISAM is poor at recovering data integrity at system crashes.</li>
<li>MyISAM has <strong>full-text</strong> search index while InnoDB has not.</li>
</ol>

<p>
In light of these differences, InnoDB and MyISAM have their unique advantages and disadvantages against each other. They each are more suitable in some scenarios than the other.
</p>

<h3>Advantages of InnoDB</h3>

<ol>
<li>InnoDB should be used where <strong>data integrity</strong> comes a priority because it inherently takes care of them by the help of relationship constraints and transactions.</li>
<li><strong>Faster in write-intensive</strong> (inserts, updates) tables because it utilizes row-level locking and only hold up changes to the same row that's being inserted or updated.</li>
</ol>

<h3>Disadvantages of InnoDB</h3>

<ol>
<li>Because InnoDB has to take care of the different relationships between tables, database administrator and scheme creators have to <strong>take more time in designing</strong> the data models which are more complex than those of MyISAM.</li>
<li><strong>Consumes more system resources</strong> such as RAM. As a matter of fact, it is recommended by many that InnoDB engine be turned off if there's no substantial need for it after installation of MySQL.</li>
<li><strong>No full-text indexing</strong>.</li>
</ol>

<h3>Advantages of MyISAM</h3>

<ol>
<li><strong>Simpler to design and create</strong>, thus better for beginners. No worries about the foreign relationships between tables.</li>
<li><strong>Faster than InnoDB on the whole</strong> as a result of the simpler structure thus much less costs of server resources.</li>
<li><strong>Full-text indexing</strong>.</li>
<li>Especially good for <strong>read-intensive (select) tables</strong>.</li>
</ol>

<h3>Disadvantages of MyISAM</h3>

<ol>
<li><strong>No data integrity</strong> (e.g. relationship constraints) check, which then comes a responsibility and overhead of the database administrators and application developers.</li>
<li><strong>Doesn't support transactions</strong> which is essential in critical data applications such as that of banking.</li>
<li><strong>Slower</strong> than InnoDB for tables that are frequently being inserted to or updated, because the entire table is locked for any insert or update.</li>
</ol>

<p>
The comparison is pretty straightforward. InnoDB is more suitable for data critical situations that require frequent inserts and updates. MyISAM, on the other hand, performs better with applications that don't quite depend on the data integrity and mostly just select and display the data.
</p>


<!-- PRINT: stop -->

</div> 

