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

<div class="greybox">
<h2>Should PHP run as a CGI script or as an Apache module?</h2>

<p>
There are two ways to configure Apache to use PHP:
</p>

<ol>
<li>Configure Apache to load the PHP interpreter as an <i>Apache module</i></li>
<li>Configure Apache to run the PHP interpreter as a <i>CGI binary</i></li>
</ol>

<p>
<i>(PS: Windows IIS normaly configures as CGI by the way)</i>
</p>

<p>
It is the intention of this post to provide you information relating to the configuration and recognition of each method. "In general" historically only one method or the other has been implemented, however, with the architectural changes made to PHP starting with PHP5, it has been quite common for hosting firms to configure for both. One version running as CGI and one version running as a Module. It is generally accepted more recently that running PHP as a CGI is more secure, however, running PHP as an Apache Module does have a slight performance gain and is generally how most pre-configured systems will be delivered out of the box.
</p>

<h2>What is the difference between CGI and apache Module Mode?</h2>

<p>
An <b>Apache module</b> is compiled into the Apache binary, so the PHP interpreter runs in the Apache process, meaning that when Apache spawns a child, each process already contains a binary image of PHP. A CGI is executed as a single process for each request, and must make an exec() or fork() call to the PHP executable, meaning that each request will create a new process of the PHP interpreter. Apache is much more efficient in it's ability to handle requests, and managing resources, making the Apache module slightly faster than the CGI (as well as more stable under load).
</p>

<p>
<b>CGI Mode</b> on the other hand, is more secure because the server now manages and controls access to the binaries. PHP can now run as your own user rather than the generic Apache user. This means you can put your database passwords in a file readable only by you and your php scripts can still access it! The "Group" and "Other" permissions can now be more restrictive. CGI mode is also claimed to be more flexible in many respects as you should now not see, with phpSuExec (refer <a href="http://joomlatutorials.com/joomla-tips-and-tricks/40-miscellaneous-joomla-tips/111-permissions-under-phpsuexec.html" class="external_link" rel="nofollow">Permissions under phpSuExec</a>) issues with file ownership being taken over by the Apache user, therefore you should no longer have problems under FTP when trying to access or modify files that have been uploaded through a PHP interface, such as Joomla! upload options.
</p>

<p>
If your server is configured to run PHP as an Apache module, then you will have the choice of using either php.ini or Apache .htaccess files, however, if your server runs PHP in CGI mode then you will only have the choice of using php.ini files locally to change settings, as Apache is no longer in complete control of PHP.
</p>

</div>

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

