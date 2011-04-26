<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; The Environments
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">The Environments</h1>	

<p>
f you have a look at the framework root directory, you will find two PHP files: index.php and dev.php. These files are called front controllers; all requests to the application are made through them. But why do we have two front controllers for each application? Both files point to the same application but for different environments.
</p> 

<ul>
<li>
The <strong>development</strong> environment: This is the environment used by web developers when they work on the application to add new features, fix bugs, ...
</li>
<li>
The <strong>production</strong> environment: This is the environment end users interact with.
</li>
</ul>

<p>
What makes an environment unique? In the development environment for instance, the application needs to log all the details of a request to ease debugging, but the cache system must be disabled as all changes made to the code must be taken into account right away. So, the development environment must be optimized for the developer. To help the developer debug the issue faster, InfoPotato displays the exception with all the information it has about the current request right into the browser.
</p>

<p>
But on the production environment, the cache layer should be activated and, of course, the application should display customized error messages instead of raw exceptions. So, the production environment must be optimized for performance and the user experience.
</p>

</div> 
<!-- end onecolumn -->
