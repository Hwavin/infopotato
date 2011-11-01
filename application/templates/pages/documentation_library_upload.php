<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">File Upload Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; File Upload Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/upload/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
InfoPotato's File Uploading Class permits files to be uploaded.  You can set various preferences, restricting the type and size of the files.
</p> 
 
<h2>The Process</h2> 
 
<p>Uploading a file involves the following general process:</p> 
 
<ul> 
<li>An upload form is displayed, allowing a user to select a file and upload it.</li> 
<li>When the form is submitted, the file is uploaded to the destination you specify.</li> 
<li>Along the way, the file is validated to make sure it is allowed to be uploaded based on the preferences you set.</li> 
<li>Once uploaded, the user will be shown a success message.</li> 
</ul> 
 
<p>To demonstrate this process here is brief tutorial. Afterward you'll find reference information.</p> 
 
<h2>Creating the Upload Form</h2> 
 
<p>
Using a text editor, create a form called <span class="red">upload_form.php</span>.
</p> 
 
<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$message</span><span class="p">))</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$message</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
 
<span class="nt">&lt;form</span> <span class="na">method=</span><span class="s">&quot;post&quot;</span> <span class="na">action=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">upload/post_upload/&quot;</span> <span class="na">enctype=</span><span class="s">&quot;multipart/form-data&quot;</span><span class="nt">&gt;</span>  
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;file&quot;</span> <span class="na">name=</span><span class="s">&quot;attachment&quot;</span> <span class="na">id=</span><span class="s">&quot;attachment&quot;</span> <span class="na">size=</span><span class="s">&quot;30&quot;</span> <span class="nt">/&gt;</span>   
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;submit&quot;</span> <span class="na">name=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">id=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">value=</span><span class="s">&quot;Upload&quot;</span> <span class="nt">/&gt;</span>  
<span class="nt">&lt;/form&gt;</span> 
</pre></div>  
 
<div class="notebox">
Don't forget to add enctype="multipart/form-data" in your form tag &lt;form&gt; if you want your form to upload the file.
</div>
 
<h2>The Manager</h2> 
 
<p>
Using a text editor, create a manager file called <span class="red">upload_manager.php</span>.
</p> 
 
<div class="syntax"><pre>
<span class="k">class</span> <span class="nc">Upload_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_upload_form</span> <span class="p">()</span> <span class="p">{</span> 
        <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;upload_form&#39;</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_upload_form</span> <span class="p">()</span> <span class="p">{</span> 
        <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$_FILES</span><span class="p">[</span><span class="s1">&#39;attachment&#39;</span><span class="p">])</span> <span class="o">&amp;&amp;</span> <span class="nv">$_FILES</span><span class="p">[</span><span class="s1">&#39;attachment&#39;</span><span class="p">][</span><span class="s1">&#39;name&#39;</span><span class="p">]</span> <span class="o">!==</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$_FILES</span><span class="p">[</span><span class="s1">&#39;attachment&#39;</span><span class="p">][</span><span class="s1">&#39;name&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="nb">strtolower</span><span class="p">(</span><span class="nv">$_FILES</span><span class="p">[</span><span class="s1">&#39;attachment&#39;</span><span class="p">][</span><span class="s1">&#39;name&#39;</span><span class="p">]);</span> 
				
	    <span class="nv">$config</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	        <span class="s1">&#39;upload_path&#39;</span> <span class="o">=&gt;</span> <span class="nx">APP_UPLOAD_DIR</span><span class="p">,</span> 
		<span class="s1">&#39;allowed_types&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;doc|docx&#39;</span><span class="p">,</span> 
		<span class="s1">&#39;max_size&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;2048&#39;</span><span class="p">,</span> <span class="c1">// in kilobytes</span> 
	    <span class="p">);</span> 
				
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;upload/upload_library&#39;</span><span class="p">,</span> <span class="s1">&#39;up&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span> 
            <span class="c1">// Begin to upload</span> 
	    <span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">up</span><span class="o">-&gt;</span><span class="na">run</span><span class="p">(</span><span class="s1">&#39;attachment&#39;</span><span class="p">))</span> <span class="p">{</span> 
		<span class="nv">$upload_validation</span> <span class="o">=</span> <span class="k">FALSE</span><span class="p">;</span> 
		<span class="nv">$upload_errors</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">up</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">();</span> 
	    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
		<span class="nv">$upload_validation</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
		<span class="c1">// An array containing all of the data related to the file uploaded</span> 
		<span class="nv">$upload_data</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">up</span><span class="o">-&gt;</span><span class="na">data</span><span class="p">();</span> 
	    <span class="p">}</span> 
            <span class="k">if</span> <span class="p">(</span><span class="nv">$upload_validation</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
	        <span class="c1">// Errors and submitted data to be displayed in view</span> 
	        <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		    <span class="s1">&#39;message&#39;</span> <span class="o">=&gt;</span> <span class="k">empty</span><span class="p">(</span><span class="nv">$upload_errors</span><span class="p">)</span> <span class="o">?</span> <span class="k">NULL</span> <span class="o">:</span> <span class="nv">$upload_errors</span><span class="p">,</span> 
	        <span class="p">);</span> 
	    <span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
                <span class="nv">$content_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		    <span class="s1">&#39;message&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;Upload successfully&#39;</span><span class="p">,</span> 
	        <span class="p">);</span> 
            <span class="p">}</span> 
            <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	        <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_template</span><span class="p">(</span><span class="s1">&#39;upload_form&#39;</span><span class="p">,</span> <span class="nv">$content_data</span><span class="p">),</span> 
                <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span> 
	    <span class="p">);</span> 
            <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
	<span class="p">}</span> 
    <span class="p">}</span> 
<span class="p">}</span> 
</pre></div>
 
<div class="notebox">
<strong>APP_UPLOAD_DIR</strong> is the target upload folder and this can be defined in the index.php file so that it is globally available. You'll need to set its file permissions to 777.
</div>

<h2>Test it!</h2> 
 
<p>To test your form, visit your site using a URI similar to this one:</p> 
 
<div class="tipbox">http://www.example.com/infopotato/web/index.php/<var>upload</var>/</div> 
 
<p>
You should see an upload form. Try uploading an word doc file (either a doc, or docx). 
</p> 


<h2>Preferences</h2> 
 
<p>The following preferences are available.  The default value indicates what will be used if you do not specify that preference.</p> 

<table cellspacing="0" width="100%" class="grid">  
<thead>
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
</tr> 
</thead>

<tbody>
<tr> 
<td><strong>upload_path</strong></td> 
<td>None</td> 
<td>None</td> 
<td>The path to the folder where the upload should be placed.  The folder must be writable and the path can be absolute or relative.</td> 
</tr> 
 
<tr> 
<td><strong>allowed_types</strong></td> 
<td>None</td> 
<td>None</td> 
<td>The mime types corresponding to the types of files you allow to be uploaded.  Usually the file extension can be used as the mime type.</td> 
</tr> 
 
 
<tr> 
<td><strong>file_name</strong></td> 
<td>None</td> 
<td>Desired file name</td> 
<td> 
	<p>If set CodeIgniter will rename the uploaded file to this name.  The extension provided in the file name must also be an allowed file type.</p> 
</td> 
</tr> 
 
<tr> 
<td><strong>overwrite</strong></td> 
<td>FALSE</td> 
<td>TRUE/FALSE (boolean)</td> 
<td>If set to true, if a file with the same name as the one you are uploading exists, it will be overwritten. If set to false, a number will be appended to the filename if another with the same name exists.</td> 
</tr> 
 
 
<tr> 
<td><strong>max_size</strong></td> 
<td>0</td> 
<td>None</td> 
<td>The maximum size (in kilobytes) that the file can be.  Set to zero for no limit. Note:  Most PHP installations have their own limit, as specified in the php.ini file.  Usually 2 MB (or 2048 KB) by default.</td> 
</tr> 
 
<tr> 
<td><strong>max_width</strong></td> 
<td>0</td> 
<td>None</td> 
<td>The maximum width (in pixels) that the file can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td><strong>max_height</strong></td> 
<td>0</td> 
<td>None</td> 
<td>The maximum height (in pixels) that the file can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td><strong>max_filename</strong></td> 
<td>0</td> 
<td>None</td> 
<td>The maximum length that a file name can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td><strong>encrypt_name</strong></td> 
<td>FALSE</td> 
<td>TRUE/FALSE (boolean)</td> 
<td>If set to TRUE the file name will be converted to a random encrypted string. This can be useful if you would like the file saved with a name that can not be discerned by the person uploading it.</td> 
</tr> 
 
<tr> 
<td><strong>remove_spaces</strong></td> 
<td>TRUE</td> 
<td>TRUE/FALSE (boolean)</td> 
<td>If set to TRUE, any spaces in the file name will be converted to underscores. This is recommended.</td> 
</tr> 
</tbody>

</table> 
 
<h2>Function Reference</h2> 
 
<p>The following functions are available</p> 
 
<h2>$this->upload->run()</h2> 
 
<p>Performs the upload based on the preferences you've set.  Note:  By default the upload routine expects the file to come from a form field
called <dfn>userfile</dfn>, and the form must be a "multipart type:</p> 
 
<div class="syntax"><pre>
<span class="nt">&lt;form</span> <span class="na">method=</span><span class="s">&quot;post&quot;</span> <span class="na">action=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">upload/post_upload/&quot;</span> <span class="na">enctype=</span><span class="s">&quot;multipart/form-data&quot;</span><span class="nt">&gt;</span>  
</pre></div> 

<p>If you would like to set your own field name simply pass its value to the <dfn>do_upload</dfn> function:</p> 
 
<div class="syntax"><pre>
<span class="nv">$field_name</span> <span class="o">=</span> <span class="s2">&quot;some_field_name&quot;</span><span class="p">;</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">upload</span><span class="o">-&gt;</span><span class="na">do_upload</span><span class="p">(</span><span class="nv">$field_name</span><span class="p">);</span> 
</pre></div> 
 
<h2>$this->upload->display_errors()</h2> 
 
<p>Retrieves any error messages if the <dfn>do_upload()</dfn> function returned false.  The function does not echo automatically, it
returns the data so you can assign it however you need.</p> 
 
<h3>Formatting Errors</h3> 
<p>By default the above function wraps any errors within &lt;p> tags.  You can set your own delimiters like this:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">upload</span><span class="o">-&gt;</span><span class="na">display_errors</span><span class="p">(</span><span class="s1">&#39;&lt;p&gt;&#39;</span><span class="p">,</span> <span class="s1">&#39;&lt;/p&gt;&#39;</span><span class="p">);</span> 
</pre></div>

<h2>$this->upload->data()</h2> 
 
<p>This is a helper function that returns an array containing all of the data related to the file you uploaded.
Here is the array prototype:</p> 
 
<div class="syntax"><pre>Array
(
    [file_name]    =&gt; mypic.jpg
    [file_type]    =&gt; image/jpeg
    [file_path]    =&gt; /path/to/your/upload/
    [full_path]    =&gt; /path/to/your/upload/jpg.jpg
    [raw_name]     =&gt; mypic
    [orig_name]    =&gt; mypic.jpg
    [client_name]  =&gt; mypic.jpg
    [file_ext]     =&gt; .jpg
    [file_size]    =&gt; 22.2
    [is_image]     =&gt; 1
    [image_width]  =&gt; 800
    [image_height] =&gt; 600
    [image_type]   =&gt; jpeg
    [image_size_str] =&gt; width=&quot;800&quot; height=&quot;200&quot;
)
</pre></div> 
  
<p>Here is an explanation of the above array items.</p> 
 
<table cellspacing="0" width="100%" class="grid"> 
<thead>
<tr><th>Item</th><th>Description</th></tr> 
</thead>

<tbody id="list2">
<tr><td class="td"><strong>file_name</strong></td> 
<td class="td">The name of the file that was uploaded including the file extension.</td></tr> 
 
<tr><td class="td"><strong>file_type</strong></td> 
<td class="td">The file's Mime type</td></tr> 
 
<tr><td class="td"><strong>file_path</strong></td> 
<td class="td">The absolute server path to the file</td></tr> 
 
<tr><td class="td"><strong>full_path</strong></td> 
<td class="td">The absolute server path including the file name</td></tr> 
 
<tr><td class="td"><strong>raw_name</strong></td> 
<td class="td">The file name without the extension</td></tr> 
 
<tr><td class="td"><strong>orig_name</strong></td> 
<td class="td">The original file name.  This is only useful if you use the encrypted name option.</td></tr> 
 
<tr><td class="td"><strong>client_name</strong></td> 
<td class="td">The file name as supplied by the client user agent, prior to any file name preparation or incrementing.</td></tr> 
 
<tr><td class="td"><strong>file_ext</strong></td> 
<td class="td">The file extension with period</td></tr> 
 
<tr><td class="td"><strong>file_size</strong></td> 
<td class="td">The file size in kilobytes</td></tr> 
 
<tr><td class="td"><strong>is_image</strong></td> 
<td class="td">Whether the file is an image or not.  1 = image. 0 = not.</td></tr> 
 
<tr><td class="td"><strong>image_width</strong></td> 
<td class="td">Image width.</td></tr> 
 
<tr><td class="td"><strong>image_height</strong></td> 
<td class="td">Image height</td></tr> 
 
<tr><td class="td"><strong>image_type</strong></td> 
<td class="td">Image type.  Typically the file extension without the period.</td></tr> 
 
<tr><td class="td"><strong>image_size_str</strong></td> 
<td class="td">A string containing the width and height.  Useful to put into an image tag.</td></tr> 
</tbody>
 
</table> 
<!-- PRINT: stop --> 

<?php echo isset($pager) ? $pager : ''; ?>