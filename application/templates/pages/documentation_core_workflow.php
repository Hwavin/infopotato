<div class="container"> 

<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Request Processing Workflow</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Request Processing Workflow
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/workflow/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
If you want to go deeper with InfoPotato, you should understand how information flows between the framework's layers.
</p>

<h2>Static requests &amp; Application requests</h2>
<p>
There are two types of requests, Static requests and Application requests.
</p>

<ul>
<li>
<strong>Static requests</strong> are made to the static resources and are not handled by InfoPotato. 
</li>
<li>
<strong>Application requests</strong> are requests forwarded to InfoPotato for processing.
</li>
</ul>


<h2>Application Request &rarr; Bootstrap &rarr; Dispatcher &rarr; (Manager &rarr; Response)</h2>
<p>
Every request handled by an InfoPotato application goes through the same basic lifecycle. The framework takes care of the repetitive tasks and ultimately executes a manager, which houses your custom application code. The picture below shows the basic workflow of an application request processed by InfoPotato.
</p>

<div class="content_image"> 
<p><strong>A typical workflow of InfoPotato application</strong></p> 
<img src="<?php echo STATIC_URI_BASE; ?>images/content/workflow.png" alt="InfoPotato Application Workflow" /> 
</div> 

<ul class="list_numbered"> 
<li>
<span class="big_number">1.</span>
<p>
The end user interacts (for example, by clicking on a hyperlink or entering a Web site address) with the client (browser, web services client) to make an HTTP request, and then the client sends this request to the web server.
</p>
</li> 

<li>
<span class="big_number">2.</span>
<p>
The web server receives the request and passes it to an InfoPotato application via the bootstrap script (which is the page all requests are routed through, e.g., /index.php or /dev.php). The bootstrap script is responsible for initializing the framework (setting application directories, including commonly used functions, etc.) and invoking Dispatcher.
</p>
</li> 

<li>
<span class="big_number">3.</span>
<p>
Dispatcher provides the actual incoming request analysis. It reads the request information (parses the request method, the URI segments), and determines from the URI segments which manager and manager method should be called and what parameters to pass to that manager method.
</p>
</li> 

<li>
<span class="big_number">4.</span>
<p>
The manager is invoked and prepares the resource identified by the incoming URI. Based on the request method (POST and GET are supported), the corresponding manager method will load and instantiate the components (data objects, libraries, user-defined functions, the corresponding template files) on demand. And then "push" the data to the template layer to render the output results. In other words, the manager is where your code goes: it's where you interpret the request and create a response.
</p>
</li> 

<li>
<span class="big_number">5.</span>
<p>
When the manager finishes processing the request, it sends the output result (HTTP response info, headers + body content) back to the web server and the web server forwards it to the client.
</p></li>

</ul> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

</div>
