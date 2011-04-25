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
Roughly speaking, every page or document sent from a web server to a browser is the result of the same processing sequence. 
</p>

<div class="tipbox">
<p>
There are two types of requests, <strong>Static requests</strong> and <strong>Application requests</strong>.
</p>

<p>
<strong>Static requests</strong> are made to the static resources which are actually not handled by InfoPotato. 
</p>

<p>
<strong>Application requests</strong> are requests forwarded to InfoPotato for parsing.
</p>
</div>

<h2>Requests, Manager, Response Lifecycle</h2>
<p>
Every request handled by an InfoPotato project goes through the same basic lifecycle. The framework takes care of the repetitive tasks and ultimately executes a controller, which houses your custom application code:
</p>

<ol> 
<li>
The end users interact with the clients (browsers, web services clients) and the clients send requests to the web server.
</li> 
<li>
The web server receives the request and passes it to a single front controller file (e.g., index.php, the single point of entry script) that's responsible for bootstrapping the framework;
</li> 
<li>
InfoPotato_App is the subclass of InfoPotato, which provides the actual implementation. It reads the request information (parses the request method, the URI segments), and determines from the URI segments which manager/method/parameters should be called;
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
