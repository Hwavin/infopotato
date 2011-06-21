<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Dispatcher</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Dispatcher
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/dispatcher/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
As you may already know, the web server receives the requests and passe them to an InfoPotato application via the bootstrap script (e.g., index.php, dev.php) which is responsible for instantiating the framework by invoking Dispatcher, in which all requests are intercepted and dispatched to individual Managers based on the URI requested.
</p>

<p>
Dispatching is the process of loading and instantiating a specific manager and executing a specific method with given parameters
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

<p class="tipbox">
You can think of the Dispatcher as the gatekeeper. It doesn't perform any real web or business logic, but rather delegates to Managers where the real work is done.
</p>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
<!-- end onecolumn -->
