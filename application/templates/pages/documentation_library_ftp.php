<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">FTP Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; FTP Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/ftp/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>CodeIgniter's FTP Class permits files to be transfered to a remote server. Remote files can also be moved, renamed,
and deleted.  The FTP class also includes a "mirroring" function that permits an entire local directory to be recreated remotely via FTP.</p> 
 
<p class="tipbox">
<strong>Note:</strong>&nbsp; SFTP and SSL FTP protocols are not supported, only standard FTP.
</p> 
 
<h2>Usage Examples</h2> 
 
<p>In this example a connection is opened to the FTP server, and a local file is read and uploaded in ASCII mode. The
file permissions are set to 755.  Note: Setting permissions requires PHP 5.</p> 
 
<div class="syntax"><pre> 
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;hostname&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;ftp.example.com&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;username&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-username&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;password&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-password&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;debug&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load</span><span class="o">-&gt;</span><span class="na">library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp/ftp_library&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">upload</span><span class="p">(</span><span class="s1">&#39;/local/path/to/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;ascii&#39;</span><span class="p">,</span> <span class="m">0775</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">close</span><span class="p">();</span> 
</pre></div> 
 
 
<p>In this example a list of files is retrieved from the server.</p> 
 
<div class="syntax"><pre>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;hostname&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;ftp.example.com&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;username&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-username&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;password&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-password&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;debug&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load</span><span class="o">-&gt;</span><span class="na">library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp/ftp_library&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
<span class="nv">$list</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">list_files</span><span class="p">(</span><span class="s1">&#39;/public_html/&#39;</span><span class="p">);</span> 
<span class="nb">print_r</span><span class="p">(</span><span class="nv">$list</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">close</span><span class="p">();</span> 
</pre></div> 
 
<p>In this example a local directory is mirrored on the server.</p> 
 
 
<div class="syntax"><pre>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;hostname&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;ftp.example.com&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;username&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-username&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;password&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-password&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;debug&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load</span><span class="o">-&gt;</span><span class="na">library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp/ftp_library&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">mirror</span><span class="p">(</span><span class="s1">&#39;/path/to/myfolder/&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/myfolder/&#39;</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">close</span><span class="p">();</span> 
</pre></div> 
 
 
<h1>Function Reference</h1> 
 
<h2>$this->ftp->connect()</h2> 
 
<p>Connects and logs into to the FTP server. Connection preferences are set by passing an array
to the function, or you can store them in a config file.</p> 
 
 
<p>Here is an example showing how you set preferences manually:</p> 
 
<div class="syntax"><pre>
<span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span> <span class="p">(</span> 
    <span class="s1">&#39;hostname&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;ftp.example.com&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;username&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-username&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;password&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;your-password&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;debug&#39;</span> <span class="o">=&gt;</span> <span class="k">TRUE</span><span class="p">,</span> 
    <span class="s1">&#39;port&#39;</span> <span class="o">=&gt;</span> <span class="m">21</span><span class="p">,</span> 
    <span class="s1">&#39;passive&#39;</span> <span class="o">=&gt;</span> <span class="k">FALSE</span> 
<span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load</span><span class="o">-&gt;</span><span class="na">library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp/ftp_library&#39;</span><span class="p">,</span> <span class="s1">&#39;ftp&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
</pre></div> 
 
<h3>Setting FTP Preferences in a Config File</h3> 
 
<p>If you prefer you can store your FTP preferences in a config file.
Simply create a new file called the <var>ftp.php</var>,  add the <var>$config</var> 
array in that file. Then save the file at <var>config/ftp.php</var> and it will be used automatically.</p> 
 
<h3>Available connection options:</h3> 
 
 
<ul> 
<li><strong>hostname</strong> - the FTP hostname.  Usually something like:&nbsp; <dfn>ftp.example.com</dfn></li> 
<li><strong>username</strong> - the FTP username.</li> 
<li><strong>password</strong> - the FTP password.</li> 
<li><strong>port</strong> - The port number. Set to <dfn>21</dfn> by default.</li> 
<li><strong>debug</strong> - <kbd>TRUE/FALSE</kbd> (boolean). Whether to enable debugging to display error messages.</li> 
<li><strong>passive</strong> - <kbd>TRUE/FALSE</kbd> (boolean). Whether to use passive mode.  Passive is set automatically by default.</li> 
</ul> 
 
 
 
<h2>$this->ftp->upload()</h2> 
 
<p>Uploads a file to your server.  You must supply the local path and the remote path, and you can optionally set the mode and permissions.
Example:</p> 
 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">upload</span><span class="p">(</span><span class="s1">&#39;/local/path/to/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;ascii&#39;</span><span class="p">,</span> <span class="m">0775</span><span class="p">);</span> 
</pre></div> 

<p><strong>Mode options are:</strong>&nbsp; <kbd>ascii</kbd>, <kbd>binary</kbd>, and <kbd>auto</kbd> (the default). If
<kbd>auto</kbd> is used it will base the mode on the file extension of the source file.</p> 
 
<p>Permissions are available if you are running PHP 5 and can be passed as an <kbd>octal</kbd> value in the fourth parameter.</p> 
 
 
<h2>$this->ftp->download()</h2> 
 
<p>Downloads a file from your server.  You must supply the remote path and the local path, and you can optionally set the mode.
Example:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">download</span><span class="p">(</span><span class="s1">&#39;/public_html/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;/local/path/to/myfile.html&#39;</span><span class="p">,</span> <span class="s1">&#39;ascii&#39;</span><span class="p">);</span> 
</pre></div> 

<p><strong>Mode options are:</strong>&nbsp; <kbd>ascii</kbd>, <kbd>binary</kbd>, and <kbd>auto</kbd> (the default). If
<kbd>auto</kbd> is used it will base the mode on the file extension of the source file.</p> 
 
<p>Returns FALSE if the download does not execute successfully (including if PHP does not have permission to write the local file)</p> 
 
 
<h2>$this->ftp->rename()</h2> 
<p>Permits you to rename a file.  Supply the source file name/path and the new file name/path.</p> 
 
<div class="syntax"><pre>
<span class="c1">// Renames green.html to blue.html</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">rename</span><span class="p">(</span><span class="s1">&#39;/public_html/foo/green.html&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/foo/blue.html&#39;</span><span class="p">);</span> 
</pre></div> 
 
<h2>$this->ftp->move()</h2> 
<p>Lets you move a file.  Supply the source and destination paths:</p> 
 
<div class="syntax"><pre>
<span class="c1">// Moves blog.html from &quot;joe&quot; to &quot;fred&quot;</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">move</span><span class="p">(</span><span class="s1">&#39;/public_html/joe/blog.html&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/fred/blog.html&#39;</span><span class="p">);</span> 
</pre></div> 
 
<p>Note: if the destination file name is different the file will be renamed.</p> 
 
 
<h2>$this->ftp->delete_file()</h2> 
<p>Lets you delete a file.  Supply the source path with the file name.</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">delete_file</span><span class="p">(</span><span class="s1">&#39;/public_html/joe/blog.html&#39;</span><span class="p">);</span> 
</pre></div> 
 
 
<h2>$this->ftp->delete_dir()</h2> 
<p>Lets you delete a directory and everything it contains.  Supply the source path to the directory with a trailing slash.</p> 
 
<p><strong>Important</strong>&nbsp; Be VERY careful with this function.  It will recursively delete
<b>everything</b> within the supplied path, including sub-folders and all files.  Make absolutely sure your path is correct.
Try using the <kbd>list_files()</kbd> function first to verify that your path is correct.</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">delete_dir</span><span class="p">(</span><span class="s1">&#39;/public_html/path/to/folder/&#39;</span><span class="p">);</span> 
</pre></div>
 
 
 
<h2>$this->ftp->list_files()</h2> 
<p>Permits you to retrieve a list of files on your server returned as an <dfn>array</dfn>.  You must supply
the path to the desired directory.</p> 
 
<div class="syntax"><pre>
<span class="nv">$list</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">list_files</span><span class="p">(</span><span class="s1">&#39;/public_html/&#39;</span><span class="p">);</span> 
<span class="nb">print_r</span><span class="p">(</span><span class="nv">$list</span><span class="p">);</span> 
</pre></div> 
 
 
<h2>$this->ftp->mirror()</h2> 
 
<p>Recursively reads a local folder and everything it contains (including sub-folders) and creates a
mirror via FTP based on it.  Whatever the directory structure of the original file path will be recreated on the server.
You must supply a source path and a destination path:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">mirror</span><span class="p">(</span><span class="s1">&#39;/path/to/myfolder/&#39;</span><span class="p">,</span> <span class="s1">&#39;/public_html/myfolder/&#39;</span><span class="p">);</span> 
</pre></div> 
 
<h2>$this->ftp->mkdir()</h2> 
 
<p>Lets you create a directory on your server.  Supply the path ending in the folder name you wish to create, with a trailing slash.
Permissions can be set by passed an <kbd>octal</kbd> value in the second parameter (if you are running PHP 5).</p> 
 
<div class="syntax"><pre>
<span class="c1">// Creates a folder named &quot;bar&quot;</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">mkdir</span><span class="p">(</span><span class="s1">&#39;/public_html/foo/bar/&#39;</span><span class="p">,</span> <span class="m">0666</span><span class="p">);</span> 
</pre></div> 
 
 
<h2>$this->ftp->chmod()</h2> 
 
<p>Permits you to set file permissions.  Supply the path to the file or folder you wish to alter permissions on:</p> 
 
<div class="syntax"><pre>
<span class="c1">// Chmod &quot;bar&quot; to 777</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">ftp</span><span class="o">-&gt;</span><span class="na">chmod</span><span class="p">(</span><span class="s1">&#39;/public_html/foo/bar/&#39;</span><span class="p">,</span> <span class="m">0666</span><span class="p">);</span> 
</pre></div> 
 
 
<h2>$this->ftp->close();</h2> 
<p>Closes the connection to your server.  It's recommended that you use this when you are finished uploading.</p> 
<!-- PRINT: stop --> 

<?php echo isset($pager) ? $pager : ''; ?>
 
</div> 
<!-- end onecolumn -->
