<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Installation</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; Installation
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/installation/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Installation of InfoPotato mainly involves the following steps:
</p> 


<div class="quote_item">
<div class="quote_author">
<img src="<?php echo STATIC_URI_BASE; ?>images/authors/george_bernard_shaw.jpg" class="quote_author_photo round_corner_img" width="40" height="40" />
<span class="quote_author_name">George Bernard Shaw<span>
</div>

<blockquote>
<div class="quote_content">
<span class="curly_quote_open"></span>
<span class="quote_text">Activity is the only road to knowledge.</span>
<span class="curly_quote_close"></span>
</div>
</blockquote>
</div>

<ul class="list_numbered"> 
<li>
<span class="big_number">1.</span>
<p>
Download the lastest InfoPotato framework and then unpack the release file to a web-accessible directory.
</p>
</li> 

<li>
<span class="big_number">2.</span>
<p>
Check your local environment settings by using the Requirements Checker tool to make sure your local development environment meets the minimal requirements.
</p>
</li> 

<li>
<span class="big_number">3.</span>
<p>
Open the bootstrap file (e.g., index.php) with a text editor and set the Environment Mode, your timezone, base URI, and default manager/method, permitted URI characters, and other application related directories.
</p>
</li> 

</ul> 


<h2>Database Config</h2>
<p>
If you intend to use a database, open the application/configs/database.php file with a text editor and set your database settings.
</p>

<h2>Increase Security</h2>
<p>
If you wish to increase security by hiding the location of your InfoPotato files you can rename the system and application folders to something more private. If you do rename them, you must open your main index.php file and set the SYS_DIR and APP_DIR variables at the top of the file with the new name you've chosen.
</p>

<p class="notebox">
To make your installation secure from the start, you should put InfoPotato system folder out of the Web-accessible directory. Only the entry script (index.php) and static resources (like stylesheets, JavaScripts and images.) needs to be exposed to Web users. All other PHP files can be hidden from the browser, which is a good idea as far as security is concerned. 
</p>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>