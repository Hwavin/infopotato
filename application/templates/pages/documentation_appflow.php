<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Application Execution Flow
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Application Execution Flow</h1>	

<p>
If you want to go deeper with InfoPotato, you should understand how information flows between the framework's layers.
</p>

<div class="tipbox">
<p>
There are two types of requests, <strong>Static requests</strong> and <strong>Application requests</strong>.
</p>

<ul>
<li>
<strong>Static requests</strong> are made to the static resources and are not handled by InfoPotato. 
</li>
<li>
<strong>Application requests</strong> are requests forwarded to InfoPotato for parsing.
</li>
</ul>
</div>

<h2>Request -> Dispatcher - > (Manager -> Response) Lifecycle</h2>
<p>
Every request handled by an InfoPotato application goes through the same basic lifecycle. The framework takes care of the repetitive tasks and ultimately executes a manager, which houses your custom application code:
</p>

<ol class="greenbox"> 
<li>
The end user interacts with the client (browser, web services client) and the client sends a request to the web server.
</li> 
<li>
The web server receives the request and passes it to a entry script (e.g., index.php, dev.php) which is responsible for bootstrapping the framework by invoking App_Dispatcher;
</li> 
<li>
App_Dispatcher is the subclass of the core Dispatcher, which provides the actual request analysis. It reads the request information (parses the request method, the URI segments), and determines from the URI segments which manager and manager method should be called and what parameters to pass to the manager method;
</li> 
<li>
The manager is executed and the code inside the manaher prepares the resource identified by the incoming URI. Based on the request method (POST and GET are supported), the corresponding manager method will load and instantiate the needed resources (data objects, libraries, user-defined functions, the corresponding template files).
</li> 
<li>
The HTTP headers and content of the response are sent back to the client (web browser or web service client).
</li>
</ol> 
 

</div> 
<!-- end onecolumn -->
