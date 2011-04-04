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
Capture the browser request in a <tt class="xref docutils literal"><span class="pre">Request</span></tt> object.
</li> 
<li>
Pass the <tt class="xref docutils literal"><span class="pre">Request</span></tt> object to the <tt class="xref docutils literal"><span class="pre">run()</span></tt> method of the application
object.
</li> 
<li>
Application locates the Python code for processing the browser request.
</li> 
<li>
Page processing code runs one or more Albatross templates.
</li> 
<li>
Templates contain either standard Albatross tags or application defined extension tags.
</li> 
<li>
As tags are converted to HTML a stream of content fragments is sent to the execution context.
</li> 
<li>
When the execution context content is flushed all of the fragments are joined together.
</li> 
<li>
Joined flushed content is sent to the <tt class="xref docutils literal"><span class="pre">Request</span></tt> object <tt class="xref docutils literal"><span class="pre">write_content()</span></tt> method.
</li> 
<li>
Application response is returned to the browser.
</li> 
</ol> 

<p>
InfoPotato implements a centralized PHP script that is called index.php which handles all HTTP requests on a web site. This entry script is responsible for initializing the framework: loading files, reading configuration data, parsing the URL into actionable information, and populating the objects that encapsulate the request. Finally, it is responsible for initializing the presenter/action.
</p>

<p>
After bootstrapping via index.php, a class called InfoPotato is instantiated and takes over. This class is responsible for interpreting request variables and parameters to a user-defined class, which is called a Worker. Usually, an worker will implement a standard interface or descend from an abstract class.
</p>

<p>
The InfoPotato will then invoke a default method called process(). This method is responsible for all the dirty work. In this method, you will load and instantiate model classes, any needed libraries, fetch the corresponding view files and output the rendered view to browser and some requesting web services.
</p>
 
</div> 
<!-- end onecolumn -->
