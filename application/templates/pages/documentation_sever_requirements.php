<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Sever Requirements
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Sever Requirements</h1>	

<ul>
<li>OS Platform: Windows or Linux</li>
<li>HTTP server: Apache, NginX, Lighttpd, IIS, etc.</li>
<li>PHP version: 5.1.6 or newer.</li>
<li>(Optional) Database: A Database is required for most web application programming. Current supported databases are MySQL (4.1+), MySQLi, MS SQL, Postgres, Oracle, SQLite, and ODBC.</li>
</ul>

<h2>Requirements Checker</h2>

<p>
Part of the distribution package is a tool called the Requirements Checker. It is a simple script that tests a server runtime environment and consequently on whether (and how) it possible to use InfoPotato.
</p>

<p>
Type <span class="red">http://localhost/infopotato/requirements_check/checker.php</span> in yuor browser and then you can easily find out if your server is to understand InfoPotato.
</p>

<h2>Recommended setup</h2>

<p>
It is recommended you use a robust platform comprised of the Linux operating system, and either the Apache web-server or the <a href="http://nginx.net/" class="external_link">NGINX</a> web-server. Almost any server that supports PHP will work. If your host doesn't support one of these platforms, and mod_rewrite, you will probably be better off switching to one of the many hosting providers that do offer those choices.
</p>

<p>
It is also <b>essential</b> that your host allows remote connections, for many of the WordPress features to work. If your host blocks outgoing HTTP connections, many parts of the WordPress will not function.
</p>

<h2>Local Testing</h2>

<p>
If you intend to install InfoPotato on your local computer (for testing) there are a number of packages that you can use.
</p>

<ul>
<li><a href="http://www.apachefriends.org/en/xampp.html" class="external_link">XAMPP</a> - Multi-platform - Apache, MySQL, PHP</li>
<li><a href="http://www.wampserver.com/en/" class="external_link">Wampserver</a> - Windows platform - Apache, MySQL, PHP, PhpMyAdmin</li>
<li><a href="http://www.easyphp.org/" class="external_link">EasyPHP</a> - Windows platform - Apache, MySQL, PHP, PhpMyAdmin, Xdebug</li>
<li>Install Apache, MySQL, PHP separately and configure them all to work together.</li>
</ul>

</div> 
<!-- end onecolumn -->
