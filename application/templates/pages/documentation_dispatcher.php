<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Dispatcher
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Dispatcher</h1>	

<h2>What is Dispatcher and what does it do?</h2>
<p>
In InfoPotato, dispatcher represents the execution context of request processing. In brief, dispatcher translates URI to manager-method-paramter triads. In detail, the dispatch process can be describes as follows:
</p> 

<ul>
<li>receives incoming request inputs from the web server</li>
<li>gets the request method (only 'GET' and 'POST' are supported) and parses the URI based on the PATH_INFO pattern</li>
<li>filters URI segments for malicious characters based on the PERMITTED_URI_CHARS</li>
<li>invokes the appropriate manager/method and passes parameters to the manager method</li>
<li>hands over control to the invoked manager and manager method which will actually processes the request</li>
</ul>

<h2>What is App_Dispatcher and what does it do?</h2>
<p>
App_Dispatcher is a subclass of Dispatcher. It gives the developers the option to customize the dispatch process.
</p>

</div> 
<!-- end onecolumn -->
