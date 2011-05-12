<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 

<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Glossary</h1>	
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Glossary
</div>
<!-- end breadcrumb -->

<h2>Single Point of Entry</h2>
<p>
In InfoPotato, all the application requests are handled by a single point of entry script, which is index.php or dev.php. Their job is to bootstrap the InfoPotato application.
</p>

<h2>Dispatcher</h2>
<p>
Typically, all application requests are handled by executing the same Dispatcher, whose job is to read the request information (parses the request method, the URI segments), and determine from the URI segments which manager/method/parameters should be called.
</p>

<h2>Manager</h2>
<p>
A manager is simply a page controller object which groups together a number of related methods that can be performed on a single entity or business object.
</p>

<h2>Data</h2>
<p>
Data provides functions to collect, manipulate and format data used in InfoPotato application in a convenient way for programmers in either SQL or NoSQL approaches. They can for example perform calculations and return the resuls or perform any other kind of backend processing.
</p>

<h2>Template</h2>
<p>
Generally, tamplate is the HTML of your pages, is what users will see, and it allows very litle php embedded, which must be just for displaying data, no real manipulation nor logic should be done in the template.
</p>


</div> 
<!-- end onecolumn -->
