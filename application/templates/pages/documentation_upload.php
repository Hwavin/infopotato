<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_ENTRY_URI; ?>home">Home</a> &gt; <a href="<?php echo APP_ENTRY_URI; ?>documentation/">Documentation</a> &gt; File Upload
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">File Upload</h1>	

<p>InfoPotato's File Uploading Class permits files to be uploaded.  You can set various
preferences, restricting the type and size of the files.</p> 
 
 
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
 
 
 
<p>Using a text editor, create a form called <dfn>upload_form.php</dfn>.  In it, place this code and save it to your <samp>applications/views/</samp> 
folder:</p> 
 
 
<textarea class="textarea" style="width:100%" cols="50" rows="23"> 
&lt;html>
&lt;head>
&lt;title>Upload Form&lt;/title>
&lt;/head>
&lt;body>
 
&lt;?php echo $error;?>
 
&lt;?php echo form_open_multipart('upload/do_upload');?>
 
&lt;input type="file" name="userfile" size="20" />
 
&lt;br />&lt;br />
 
&lt;input type="submit" value="upload" />
 
&lt;/form>
 
&lt;/body>
&lt;/html></textarea> 
 
<p>You'll notice we are using a form helper to create the opening form tag.  File uploads require a multipart form, so the helper
creates the proper syntax for you.  You'll also notice we have an $error variable.  This is so we can show error messages in the event
the user does something wrong.</p> 
 
 
<h2>The Success Page</h2> 
 
<p>Using a text editor, create a form called <dfn>upload_success.php</dfn>.
In it, place this code and save it to your <samp>applications/views/</samp> folder:</p> 
 
<textarea class="textarea" style="width:100%" cols="50" rows="20">&lt;html>
&lt;head>
&lt;title>Upload Form&lt;/title>
&lt;/head>
&lt;body>
 
&lt;h3>Your file was successfully uploaded!&lt;/h3>
 
&lt;ul>
&lt;?php foreach($upload_data as $item => $value):?>
&lt;li>&lt;?php echo $item;?>: &lt;?php echo $value;?>&lt;/li>
&lt;?php endforeach; ?>
&lt;/ul>
 
&lt;p>&lt;?php echo anchor('upload', 'Upload Another File!'); ?>&lt;/p>
 
&lt;/body>
&lt;/html></textarea> 
 
 
<h2>The Controller</h2> 
 
<p>Using a text editor, create a controller called <dfn>upload.php</dfn>.  In it, place this code and save it to your <samp>applications/controllers/</samp> 
folder:</p> 
 
 
<textarea class="textarea" style="width:100%" cols="50" rows="43">&lt;?php
 
class Upload extends Controller {
 
	function Upload() {
		parent::Controller();
		$this->load->helper(array('form', 'url'));
	}
 
	function index() {
		$this->load->view('upload_form', array('error' => ' ' ));
	}
 
	function do_upload() {
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
 
		$this->load->library('upload', $config);
 
		if ( ! $this->upload->do_upload()) {
			$error = array('error' => $this->upload->display_errors());
 
			$this->load->view('upload_form', $error);
		} else {
			$data = array('upload_data' => $this->upload->data());
 
			$this->load->view('upload_success', $data);
		}
	}
}
?&gt;</textarea> 
 
 
<h2>The Upload Folder</h2> 
 
<p>You'll need a destination folder for your uploaded images.  Create a folder at the root of your CodeIgniter installation called
<dfn>uploads</dfn> and set its file permissions to 777.</p> 
 
 
<h2>Try it!</h2> 
 
<p>To try your form, visit your site using a URL similar to this one:</p> 
 
<code>example.com/index.php/<var>upload</var>/</code> 
 
<p>You should see an upload form. Try uploading an image file (either a jpg, gif, or png). If the path in your
controller is correct it should work.</p> 
 
 
<p>&nbsp;</p> 
 
<h1>Reference Guide</h1> 
 
 
<h2>Initializing the Upload Class</h2> 
 
<p>Like most other classes in CodeIgniter, the Upload class is initialized in your controller using the <dfn>$this->load->library</dfn> function:</p> 
 
<code>$this->load->library('upload');</code> 
<p>Once the Upload class is loaded, the object will be available using: <dfn>$this->upload</dfn></p> 
 
 
<h2>Setting Preferences</h2> 
 
<p>Similar to other libraries, you'll control what is allowed to be upload based on your preferences.  In the controller you
built above you set the following preferences:</p> 
 
<code>$config['upload_path'] = './uploads/';<br /> 
$config['allowed_types'] = 'gif|jpg|png';<br /> 
$config['max_size']	= '100';<br /> 
$config['max_width']  = '1024';<br /> 
$config['max_height']  = '768';<br /> 
<br /> 
$this->load_library('upload/upload_library', 'upload', $config);<br /><br /> 
 
// Alternately you can set preferences by calling the initialize function.  Useful if you auto-load the class:<br /> 
$this->upload->initialize($config);</code> 
 
<p>The above preferences should be fairly self-explanatory.  Below is a table describing all available preferences.</p> 
 
 
<h2>Preferences</h2> 
 
<p>The following preferences are available.  The default value indicates what will be used if you do not specify that preference.</p> 

<script type="text/javascript"> 
function stripedList(list) {
	var items = document.getElementById(list).getElementsByTagName('tr');
	for (i = 0; i < items.length; i++) {
		if ((i%2) ? false : true) {
			items[i].className += " odd";
		} else {
			items[i].className += " even";
		}
	}
}
window.onload = function() {
	stripedList('list1'); 
	stripedList('list2');
};
</script>

<table cellpadding="0" cellspacing="1" border="0" style="width:100%"> 
<thead>
<tr> 
<th>Preference</th> 
<th>Default&nbsp;Value</th> 
<th>Options</th> 
<th>Description</th> 
</tr> 
</thead>

<tbody id="list1">
<tr> 
<td class="td"><strong>upload_path</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The path to the folder where the upload should be placed.  The folder must be writable and the path can be absolute or relative.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>allowed_types</strong></td> 
<td class="td">None</td> 
<td class="td">None</td> 
<td class="td">The mime types corresponding to the types of files you allow to be uploaded.  Usually the file extension can be used as the mime type.  Separate multiple types with a pipe.</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>file_name</strong></td> 
<td class="td">None</td> 
<td class="td">Desired file name</td> 
<td class="td"> 
	<p>If set CodeIgniter will rename the uploaded file to this name.  The extension provided in the file name must also be an allowed file type.</p> 
</td> 
</tr> 
 
<tr> 
<td class="td"><strong>overwrite</strong></td> 
<td class="td">FALSE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">If set to true, if a file with the same name as the one you are uploading exists, it will be overwritten. If set to false, a number will be appended to the filename if another with the same name exists.</td> 
</tr> 
 
 
<tr> 
<td class="td"><strong>max_size</strong></td> 
<td class="td">0</td> 
<td class="td">None</td> 
<td class="td">The maximum size (in kilobytes) that the file can be.  Set to zero for no limit. Note:  Most PHP installations have their own limit, as specified in the php.ini file.  Usually 2 MB (or 2048 KB) by default.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>max_width</strong></td> 
<td class="td">0</td> 
<td class="td">None</td> 
<td class="td">The maximum width (in pixels) that the file can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>max_height</strong></td> 
<td class="td">0</td> 
<td class="td">None</td> 
<td class="td">The maximum height (in pixels) that the file can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>max_filename</strong></td> 
<td class="td">0</td> 
<td class="td">None</td> 
<td class="td">The maximum length that a file name can be.  Set to zero for no limit.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>encrypt_name</strong></td> 
<td class="td">FALSE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">If set to TRUE the file name will be converted to a random encrypted string. This can be useful if you would like the file saved with a name that can not be discerned by the person uploading it.</td> 
</tr> 
 
<tr> 
<td class="td"><strong>remove_spaces</strong></td> 
<td class="td">TRUE</td> 
<td class="td">TRUE/FALSE (boolean)</td> 
<td class="td">If set to TRUE, any spaces in the file name will be converted to underscores. This is recommended.</td> 
</tr> 
</tbody>

</table> 
 
 
<h2>Setting preferences in a config file</h2> 
 
<p>If you prefer not to set preferences using the above method, you can instead put them into a config file.
Simply create a new file called the <var>upload.php</var>,  add the <var>$config</var> 
array in that file. Then save the file in: <var>config/upload.php</var> and it will be used automatically. You
will NOT need to use the <dfn>$this->upload->initialize</dfn> function if you save your preferences in a config file.</p> 
 
 
<h2>Function Reference</h2> 
 
<p>The following functions are available</p> 
 
 
<h2>$this->upload->do_upload()</h2> 
 
<p>Performs the upload based on the preferences you've set.  Note:  By default the upload routine expects the file to come from a form field
called <dfn>userfile</dfn>, and the form must be a "multipart type:</p> 
 
<code>&lt;form method="post" action="some_action" enctype="multipart/form-data" /></code> 
 
<p>If you would like to set your own field name simply pass its value to the <dfn>do_upload</dfn> function:</p> 
 
<code> 
$field_name = "some_field_name";<br /> 
$this->upload->do_upload($field_name)</code> 
 
 
<h2>$this->upload->display_errors()</h2> 
 
<p>Retrieves any error messages if the <dfn>do_upload()</dfn> function returned false.  The function does not echo automatically, it
returns the data so you can assign it however you need.</p> 
 
<h3>Formatting Errors</h3> 
<p>By default the above function wraps any errors within &lt;p> tags.  You can set your own delimiters like this:</p> 
 
<code>$this->upload->display_errors('<var>&lt;p></var>', '<var>&lt;/p></var>');</code> 
 
<h2>$this->upload->data()</h2> 
 
<p>This is a helper function that returns an array containing all of the data related to the file you uploaded.
Here is the array prototype:</p> 
 
<code>Array<br /> 
(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[file_name]&nbsp;&nbsp;&nbsp;&nbsp;=> mypic.jpg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[file_type]&nbsp;&nbsp;&nbsp;&nbsp;=> image/jpeg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[file_path]&nbsp;&nbsp;&nbsp;&nbsp;=> /path/to/your/upload/<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[full_path]&nbsp;&nbsp;&nbsp;&nbsp;=> /path/to/your/upload/jpg.jpg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[raw_name]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=> mypic<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[orig_name]&nbsp;&nbsp;&nbsp;&nbsp;=> mypic.jpg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[client_name]&nbsp;&nbsp;=> mypic.jpg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[file_ext]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=> .jpg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[file_size]&nbsp;&nbsp;&nbsp;&nbsp;=> 22.2<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[is_image]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=> 1<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[image_width]&nbsp;&nbsp;=> 800<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[image_height] => 600<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[image_type]&nbsp;&nbsp;&nbsp;=> jpeg<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;[image_size_str] => width="800" height="200"<br /> 
)</code> 
 
<h3>Explanation</h3> 
 
<p>Here is an explanation of the above array items.</p> 
 
<table cellpadding="0" cellspacing="1" border="0" style="width:100%"> 
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
 
</div> 
<!-- end onecolumn -->
