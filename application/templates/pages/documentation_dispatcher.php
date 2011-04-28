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

<p>
All incoming application requests enter the framework via a single point of entry script, which is index.php or dev.php. The entry script represents the execution context of request processing. It also serves as the central place for keeping application-level configurations.
</p>

<h2>What is Dispatcher and what does it do?</h2>
<p>
In InfoPotato, dispatcher represents the execution context of the initial application request processing. In brief, dispatcher translates URI to manager-method-paramter triads. In detail, the dispatch process can be describes as follows:
</p> 

<ul>
<li>receives incoming request inputs from the web server</li>
<li>gets the request method (only 'GET' and 'POST' are supported) and parses the URI based on the PATH_INFO pattern</li>
<li>filters URI segments for malicious characters based on the PERMITTED_URI_CHARS</li>
<li>invokes the appropriate manager and corret method(s) inside that manager with URI parameters</li>
<li>hands over control to the invoked manager and manager method which will actually processes the request</li>
</ul>

<h2>What is App_Dispatcher and what does it do?</h2>
<p>
App_Dispatcher is a subclass of Dispatcher. It gives the developers the option to customize the dispatch process.
</p>

<p class="tipbox">
Think of the Dispatcher as the gatekeeper. It doesn't perform any real web or business logic, but rather delegates to Managers where the real work is done.
</p>

</div> 
<!-- end onecolumn -->
