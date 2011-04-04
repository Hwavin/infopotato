<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo BASE_URI; ?>home">Home</a> &gt; <a href="<?php echo BASE_URI; ?>documentation/">Documentation</a> &gt; Application Execution Flow
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Application Execution Flow</h1>	

<p>
Roughly speaking, every page or document sent from a web server to a browser is the result of the same processing sequence. For our purposes a document is one page in an application.
</p>

<ol> 
<li>
The browser connects to the server and requests a page.
</li> 
<li>
The server decodes the browser request and processes it.  This can cause all manner of subsidiary application processing to occur.
</li> 
<li>
The server sends the result of processing back to the browser as the next page in the application.
</li> 
</ol> 

<p>
InfoPotato handles user requests and organizes the execution flow of an application. Understanding InfoPotato architecture is key to write efficient and durable applications. The following diagram shows a typical application execution flow of an InfoPotato application when it is handling a user HTTP request:
</p> 
 
<div class="content_image">
<p>A typical workflow of InfoPotato application</p>
<img src="<?php echo BASE_URI; ?>img/content/appflow.png" width="800" height="800" alt="Application Execution Flow" />
</div> 

<ol> 
<li>
The end users interact with the clients (browsers, web services clients) and the clients send requests to the web server.
</li> 
<li>
The web server receives the request and passes it to index.php, which is the single point of entry script. InfoPotato implements a centralized PHP script that is called index.php which handles all HTTP incoming requests on a website. This entry script is responsible for initializing the framework: loading files, reading configuration data.
</li> 
<li>
Inside index.php are some required configs to run InfoPotato's application. Then it initializes an InfoPotato Application object and everything is done. After bootstrapping via index.php, a class called InfoPotato_App is instantiated and takes over. This class is responsible for interpreting request variables and parameters to a user-defined class, which is called a Worker.
</li> 
<li>
InfoPotato_App is the subclass of InfoPotato, which provides the actual implementation. Developers can write their own InfoPotato_App class and customize some functionalities of InfoPotato.
</li> 
<li>
The InfoPotato class gets the incoming request and analysizes the request method (POST, GET, PUT, DELETE) and URI, then decide which worker and method function to assign this request to for further process.
</li> 
<li>
The target worker gets the request and runs the corresponding method to do all the dirty work to prepare the request and response back to the clients with the desired resources. Based on the request method (POST, GET, PUT, DELETE), the corresponding worker method will load and instantiate data classes, any needed libraries, fetch the corresponding template files and output the rendered view to browser and some requesting web services.
</li> 
</ol> 
 
</div> 
<!-- end onecolumn -->
