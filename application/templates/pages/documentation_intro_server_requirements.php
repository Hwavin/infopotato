<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Sever Requirements</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; Sever Requirements
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/server_requirements/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<ul>
<li>OS Platform: Windows or Linux</li>
<li>HTTP server: Apache, NginX, Lighttpd, IIS, etc.</li>
<li>PHP version: 5.2.0 or newer.</li>
<li>(Optional) Database: Current supported databases are MySQL (4.1+), MySQLi, Postgres, and SQLite.</li>
</ul>

<h2>Requirements Checker</h2>

<p>
Part of the distribution package is a tool called the Requirements Checker. It is a simple script that tests a server runtime environment and consequently on whether (and how) it possible to use InfoPotato.
</p>

<p>
Type <span class="red">http://localhost/infopotato/requirements_check/checker.php</span> in yuor browser and then you can easily find out if your server is to understand InfoPotato.
</p>

<h2>Local Development/Testing</h2>

<p>
If you intend to install InfoPotato on your local computer (for testing) there are a number of Apache-MySQL-PHP packages that you can use.
</p>

<ul>
<li><a href="http://www.apachefriends.org/en/xampp.html" class="external_link">XAMPP</a> - Multi-platform - Apache, MySQL, PHP</li>
<li><a href="http://www.mamp.info/en/index.html" class="external_link">MAMP</a> - Macintosh - Apache - MySQL - PHP</li>
<li><a href="http://www.wampserver.com/en/" class="external_link">Wampserver</a> - Windows - Apache, MySQL, PHP, PhpMyAdmin</li>
<li><a href="http://www.easyphp.org/" class="external_link">EasyPHP</a> - Windows - Apache, MySQL, PHP, PhpMyAdmin, Xdebug</li>
<li>Install Apache, MySQL, PHP separately and configure them all to work together.</li>
</ul>

<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

