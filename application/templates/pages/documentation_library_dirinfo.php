<div class="row">

<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Dir Info Library</h1>	
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Dir Info Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/dirinfo/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
DirInfo can retrieve information on files, sub-directories and files within sub-directories.
</p> 

<p>
You can retrieve various kinds of lists with filenames as well as retrieve information about individual files, such as the file-size, file-last-access-date, file-last-modified-date and more.
</p>

<p>
The class defaults to image files only, but based on the parameters you pass, information on any type of file can be retrieved.
</p>
<strong>Features</strong>
<ul>
<li>Generate a filelist / directory list as an array</li>
<li>Include subdirectories / files within subdirectories ($recursion = true)</li>
<li>Create a selection within the generated filelist based on:<br />

* file extension(s)<br />

* file extension(s) + mimetype check<br />

* last modified date<br />

* last access date</li><li>Create a selection within an earlier made selection</li><li>Sort the filelist</li><li>Find out which is the most recently changed file + the timestamp</li><li>Find out the directory size of<br />

* a directory<br />

* a directory and all subdirectories within it<br />

* a selection of files within a directory<br />

* same but then including selected files in subdirectories</li><li>Check whether the file extension of a file is within an allowed list (defaults to image files)</li><li>Check whether the mime-type of a file is within an allowed list (defaults to image files)</li><li>Combine the above two checks</li><li>Get information on individual files:<br />

* filesize (optionally in a human readable format)<br />

* last modified date (optionally in a - self-defined - human readable format)<br />

* last access date (optionally in a - self-defined - human readable format)<br />

* file permissions in a human readable format<br />

* file owner id<br />

* mime-type</li>
<li>Change an arbitrary filesize to a human readable format</li>
</ul> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div>
