<div class="container"> 

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">The Directory Structure</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/intro/">Introduction</a> &gt; The Directory Structure
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/intro/structure/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
The directory structure of an InfoPotato application is rather flexible. Open framework folder, it's a basic project folder for InfoPotato. Every new app you create uses the same project structure.
</p>

<p>In a project folder you have:</p>

<ul>
<li><strong>app/:</strong> - The application source code;</li>
<li><strong>system/:</strong> - InfoPotato framework files</li>
<li><strong>web/:</strong> - The web root directory.</li>
</ul>

<p class="notebox">
The web root directory is the home of all public and static files like images, stylesheets, and JavaScript files. It is also where each front controller lives.
</p>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
