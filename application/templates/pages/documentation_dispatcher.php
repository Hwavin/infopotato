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
As you may already know, the web server receives the requests and passe them to an InfoPotato application via the bootstrap script (e.g., index.php, dev.php) which is responsible for instantiating the framework by invoking App_Dispatcher, in which all requests are intercepted and dispatched to individual Managers based on the URI requested.
</p>

<p>
Dispatching is the process of extracting the manager name, method name, and optional parameters contained in it, and then instantiating a manager and calling the appropriate method of that manager.
</p>

<h2>What is Dispatcher and what does it do?</h2>
<p>
In InfoPotato, dispatcher represents the execution context of the initial application request processing. In brief, dispatcher translates URI to manager-method-paramter triads. In detail, the dispatch process can be describes as follows:
</p> 

<ul>
<li>receives incoming request inputs from the bootscript script</li>
<li>gets the request method (only 'GET' and 'POST' are supported) and parses the URI based on the PATH_INFO pattern</li>
<li>filters URI segments for malicious characters based on the PERMITTED_URI_CHARS</li>
<li>invokes the appropriate manager and corret method(s) of that manager with URI parameters</li>
<li>hands over control to the invoked manager and manager method which will actually processes the request</li>
</ul>

<h2>What is App_Dispatcher and what does it do?</h2>
<p>
App_Dispatcher is a subclass of Dispatcher. It gives the developers the option to customize the dispatch process.
</p>

<p class="tipbox">
You can think of the Dispatcher as the gatekeeper. It doesn't perform any real web or business logic, but rather delegates to Managers where the real work is done.
</p>

</div> 
<!-- end onecolumn -->
