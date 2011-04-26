<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Installation
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Installation</h1>	

<p>
Installation of InfoPotato mainly involves the following steps:
</p> 

<div class="box_right greybox">
<blockquote>
<span>Activity is the only road to knowledge.</span>
<div>&mdash; George Bernard Shaw</div>
</blockquote>
</div>

<h2>Step 1</h2>
<p>
Download the lastest InfoPotato framework.
</p>

<h2>Step 2</h2>
<p>
Unpack the InfoPotato release file to a Web-accessible directory.
</p>

<h2>Step 3</h2>
<p>
Check your local environment by using the Requirements Checker tool.
</p>

<h2>Step 4</h2>
<p>
Open the index.php file with a text editor and set the Environment Mode, your timezone, base URI, and default manager/method, permitted URI characters, and other application related directories.
</p>

<h2>Database Config</h2>
<p>
If you intend to use a database, open the application/configs/database.php file with a text editor and set your database settings.
</p>

<h2>Increase Security</h2>
<p>
If you wish to increase security by hiding the location of your InfoPotato files you can rename the system and application folders to something more private. If you do rename them, you must open your main index.php file and set the SYS_DIR and APP_DIR variables at the top of the file with the new name you've chosen.
</p>

<p class="tipbox">
To make your installation secure from the start, you should put InfoPotato system folder out of the Web-accessible directory. Only the entry script (index.php) and static resources (like stylesheets, JavaScripts and images.) needs to be exposed to Web users. All other PHP files can be hidden from the browser, which is a good idea as far as security is concerned. 
</p>

</div> 
<!-- end onecolumn -->
