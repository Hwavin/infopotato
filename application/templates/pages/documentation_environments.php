<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">The Environments</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; The Environments
</div>
<!-- end breadcrumb -->

<p>
An application can run in various environments. The different environments share the same PHP code (apart from the front controller), but can have completely different configurations. For each application, InfoPotato provides two default environments: production, and development. You’re also free to add as many custom environments as you wish.
</p>

<p>
If you have a look at the framework root directory, you will find two PHP files: <span class="red">index.php</span> and <span class="red">dev.php</span>. These files are called <strong>front controllers</strong>; all requests to the application are made through them. But why do we have two front controllers for each application? Both files point to the same application but for different environments.
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

<p>
To change the environment in which you're browsing your application, just change the front controller. For example, you are working on the development environment now:
</p>

<div class="syntax">
http://localhost/infopotato/web/<span class="red">dev.php</span>
</div>

<p>
However, if you want to see how the application reacts in production, call the production front controller instead:
</p>

<div class="syntax">
http://localhost/infopotato/web/<span class="red">index.php</span>
</div>

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
