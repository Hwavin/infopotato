<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>tutorials/">Tutorials</a> &gt; Website Performance Tuning
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Website Performance Tuning</h1>	

<p>
Performance of Web applications is affected by many factors. Database access, file system operations, network bandwidth are all potential affecting factors.
</p>

<h2>Enabling APC Extension</h2>
<p>
Enabling the PHP APC extension is perhaps the easiest way to improve the overall performance of an application. The extension caches and optimizes PHP intermediate code and avoids the time spent in parsing PHP scripts for every incoming request.
</p>

<h2>Using Caching Techniques</h2>
<p>
As described in the Caching section, Yii provides several caching solutions that may improve the performance of a Web application significantly.
</p>

<h2>Database Optimization</h2>

<h2>Minimizing Script Files</h2>
<p>
Complex pages often need to include many external JavaScript and CSS files. Because each file would cause one extra round trip to the server and back, we should minimize the number of script files by merging them into fewer ones. We should also consider reducing the size of each script file to reduce the network transmission time. There are many tools around to help on these two aspects.
</p>

</div> 
<!-- end onecolumn -->
