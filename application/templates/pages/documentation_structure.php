<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Structure of a Project
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Structure of a Project</h1>	

<p>
Open framework folder, it's a basic project folder for InfoPotato. Every new app you create uses the same project structure.
</p>

<div class="content_image">
<p>A typical workflow of Yii application</p>
<img src="<?php echo APP_URI; ?>application/images/content/project_structure.jpg" width="330" height="113" alt="Structure of a Project" />
</div>
 
<p>In a project folder you have:</p>

<ul>
<li><strong>cache</strong> - place to store your cache files.</li>
<li><strong>class</strong> - put your useful 3rd party PHP class here to be used with InfoPotato</li>
<li><strong>config</strong> - the 3 configuration files are here: common, routes, database configs.</li>
<li><strong>controller</strong> - Your Controller classes here. There's a <em>MainController</em> by default.</li>
<li><strong>model</strong> - Your Model classes here.</li>
<li><strong>plugin</strong> - there's a file called template_tags.php for you to add features to the View</li>
<li><strong>view</strong> - Your Html template files here.</li>
<li><strong>viewc</strong> - Consist of compiled template files.</li>
</ul>

<p>
An InfoPotato application is broken down into three big categories of components: Workers, Templates, and Data/Library/Script. InfoPotato has the ability to host multiple apps simultaneously.
</p>

</div> 
<!-- end onecolumn -->
