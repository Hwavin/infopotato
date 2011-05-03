<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Request Processing Workflow
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Request Processing Workflow</h1>	

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
Every request handled by an InfoPotato application goes through the same basic lifecycle. The framework takes care of the repetitive tasks and ultimately executes a manager, which houses your custom application code. The picture below shows the basic workflow of an application request processed by InfoPotato.
</p>

<div class="content_image"> 
<p><strong>A typical workflow of InfoPotato application</strong></p> 
<img src="<?php echo STATIC_URI_BASE; ?>images/content/workflow.png" width="886" height="314" alt="InfoPotato Application Workflow" /> 
</div> 

<ol> 
<li>
The end user interacts (for example, by clicking on a hyperlink or entering a Web site address) with the client (browser, web services client) to make an HTTP request, and then the client sends this request to the web server.
</li> 
<li>
The web server receives the request and passes it to an InfoPotato application via a entry script (e.g., index.php, dev.php) which is responsible for bootstrapping the framework by invoking App_Dispatcher.
</li> 
<li>
App_Dispatcher is the subclass of the core Dispatcher, which provides the actual incoming request analysis. It reads the request information (parses the request method, the URI segments), and determines from the URI segments which manager and manager method should be called and what parameters to pass to the manager method.
</li> 
<li>
The manager is invoked and prepares the resource identified by the incoming URI. Based on the request method (POST and GET are supported), the corresponding manager method will load and instantiate the components (data objects, libraries, user-defined functions, the corresponding template files) on demand.
</li> 
<li>
When the manager finishes processing the request, it sends the HTTP response info (headers + body content) back to the web server and the web server forwards it to the client.
</li>
</ol> 
 

</div> 
<!-- end onecolumn -->
