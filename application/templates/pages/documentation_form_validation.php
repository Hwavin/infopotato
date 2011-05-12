<!-- begin breadcrumb -->
<div id="breadcrumb">
<div class="inner">
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Form validation Library
</div>
</div>
<!-- end breadcrumb -->
<div class="clear"></div>

<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
<h1 class="first_heading">Form Validation Library</h1>	

<p>InfoPotato provides a comprehensive form validation and data prepping class that helps minimize the amount of code you'll write.</p> 

<h2>Overview</h2> 
 
<p>Before explaining InfoPotato's approach to data validation, let's describe the ideal scenario:</p> 
 
<ol class="greenbox"> 
<li>A form is displayed.</li> 
<li>You fill it in and submit it.</li> 
<li>If you submitted something invalid, or perhaps missed a required item, the form is redisplayed containing your data
along with an error message describing the problem.</li> 
<li>This process continues until you have submitted a valid form.</li> 
</ol> 
 
<p>On the receiving end, the script must:</p> 
 
<ol class="greenbox"> 
<li>Check for required data.</li> 
<li>Verify that the data is of the correct type, and meets the correct criteria. For example, if a username is submitted
it must be validated to contain only permitted characters.  It must be of a minimum length,
and not exceed a maximum length. The username can't be someone else's existing username, or perhaps even a reserved word. Etc.</li> 
<li>Sanitize the data for security.</li> 
<li>Pre-format the data if needed (Does the data need to be trimmed?  HTML encoded?  Etc.)</li> 
<li>Prep the data for insertion in the database or other purposes.</li> 
</ol> 
 
 
<p>Although there is nothing terribly complex about the above process, it usually requires a significant
amount of code, and to display error messages, various control structures are usually placed within the form HTML.
Form validation, while simple to create, is generally very messy and tedious to implement.</p> 

<h2>Implimentation in Detail</h2>
<p>In order to implement form validation you'll need three things:</p> 
 
<ul  class="greenbox"> 
<li>A template file containing a form which can display messages upon submission.</li> 
<li>A manager to receive and process the submitted data.</li> 
</ul> 
 
<p>Let's create those three things, using a member sign-up form as the example.</p> 
 
<h2>The Contact Form</h2> 
 
<p>
Using a text editor, create a form called <span class="red">contact.php</span>.
</p> 
 
<div class="syntax"><pre><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$errors</span><span class="p">))</span> <span class="o">:</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nv">$errors</span><span class="p">;</span> <span class="cp">?&gt;</span> 
<span class="cp">&lt;?php</span> <span class="k">endif</span><span class="p">;</span> <span class="cp">?&gt;</span> 
 
<span class="nt">&lt;form</span> <span class="na">id=</span><span class="s">&quot;contact_form&quot;</span> <span class="na">method=</span><span class="s">&quot;post&quot;</span> <span class="na">action=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">contact/&quot;</span><span class="nt">&gt;</span>  
 
Subject*: <span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;text&quot;</span> <span class="na">name=</span><span class="s">&quot;contact_subject&quot;</span> <span class="na">id=</span><span class="s">&quot;contact_subject&quot;</span> <span class="na">value=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$contact_subject</span><span class="p">))</span> <span class="k">echo</span> <span class="nv">$contact_subject</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="nt">/&gt;</span>   
 
Message*: <span class="nt">&lt;textarea</span> <span class="na">name=</span><span class="s">&quot;contact_message&quot;</span> <span class="na">id=</span><span class="s">&quot;contact_message&quot;</span><span class="nt">&gt;</span><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$contact_message</span><span class="p">))</span> <span class="k">echo</span> <span class="nv">$contact_message</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="nt">&lt;/textarea&gt;</span> 
 
Name*: <span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;text&quot;</span> <span class="na">name=</span><span class="s">&quot;contact_name&quot;</span> <span class="na">id=</span><span class="s">&quot;contact_name&quot;</span> <span class="na">value=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$contact_name</span><span class="p">))</span> <span class="k">echo</span> <span class="nv">$contact_name</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="nt">/&gt;</span>   
 
Email*: <span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;text&quot;</span> <span class="na">name=</span><span class="s">&quot;contact_email&quot;</span> <span class="na">id=</span><span class="s">&quot;contact_email&quot;</span> <span class="na">value=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">if</span> <span class="p">(</span><span class="nb">isset</span><span class="p">(</span><span class="nv">$contact_email</span><span class="p">))</span> <span class="k">echo</span> <span class="nv">$contact_email</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">&quot;</span> <span class="nt">/&gt;</span>   
 
<span class="nt">&lt;input</span> <span class="na">type=</span><span class="s">&quot;submit&quot;</span> <span class="na">name=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">id=</span><span class="s">&quot;submit_btn&quot;</span> <span class="na">value=</span><span class="s">&quot;Send&quot;</span> <span class="nt">/&gt;</span>  
 
<span class="nt">&lt;/form&gt;</span> 
</pre></div> 
 

<h2>The Manager</h2> 
 
<p>
Using a text editor, create a manager file called <span class="red">contact_manager.php</span>.
</p> 
 
<div class="syntax"><pre><span class="cp">&lt;?php</span> 
<span class="k">final</span> <span class="k">class</span> <span class="nc">Contact_Manager</span> <span class="k">extends</span> <span class="nx">Manager</span> <span class="p">{</span> 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
	    <span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;contact&#39;</span><span class="p">),</span> 
	    <span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	<span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
    <span class="p">}</span> 
 
    <span class="k">public</span> <span class="k">function</span> <span class="nf">post_index</span><span class="p">()</span> <span class="p">{</span> 
	<span class="c1">// Load Form Validation library and assign post data</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;form_validation/form_validation_library&#39;</span><span class="p">,</span> <span class="s1">&#39;fv&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;post&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">));</span> 
 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;contact_subject&#39;</span><span class="p">,</span> <span class="s1">&#39;Subject&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|required&#39;</span><span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;contact_message&#39;</span><span class="p">,</span> <span class="s1">&#39;Message&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|required&#39;</span><span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;contact_name&#39;</span><span class="p">,</span> <span class="s1">&#39;Name&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|required&#39;</span><span class="p">);</span> 
	<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_rules</span><span class="p">(</span><span class="s1">&#39;contact_email&#39;</span><span class="p">,</span> <span class="s1">&#39;Email&#39;</span><span class="p">,</span> <span class="s1">&#39;trim|required|valid_email&#39;</span><span class="p">);</span> 
		
	<span class="nv">$result</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">run</span><span class="p">();</span> 
			
	<span class="nv">$contact_subject</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;contact_subject&#39;</span><span class="p">);</span> 
	<span class="nv">$contact_message</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;contact_message&#39;</span><span class="p">);</span> 
	<span class="nv">$contact_name</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;contact_name&#39;</span><span class="p">);</span> 
	<span class="nv">$contact_email</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">set_value</span><span class="p">(</span><span class="s1">&#39;contact_email&#39;</span><span class="p">);</span> 
 
	<span class="k">if</span> <span class="p">(</span><span class="nv">$result</span> <span class="o">===</span> <span class="k">FALSE</span><span class="p">)</span> <span class="p">{</span> 
	    <span class="nv">$errors</span> <span class="o">=</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">fv</span><span class="o">-&gt;</span><span class="na">form_errors</span><span class="p">();</span> 
			
	    <span class="c1">// Errors and submitted data to be displayed in view</span> 
	    <span class="nv">$data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;errors&#39;</span> <span class="o">=&gt;</span> <span class="k">empty</span><span class="p">(</span><span class="nv">$errors</span><span class="p">)</span> <span class="o">?</span> <span class="k">NULL</span> <span class="o">:</span> <span class="nv">$errors</span><span class="p">,</span> 
		<span class="s1">&#39;contact_subject&#39;</span> <span class="o">=&gt;</span> <span class="nv">$contact_subject</span><span class="p">,</span> 
		<span class="s1">&#39;contact_message&#39;</span> <span class="o">=&gt;</span> <span class="nv">$contact_message</span><span class="p">,</span> 
		<span class="s1">&#39;contact_name&#39;</span> <span class="o">=&gt;</span> <span class="nv">$contact_name</span><span class="p">,</span> 
		<span class="s1">&#39;contact_email&#39;</span> <span class="o">=&gt;</span> <span class="nv">$contact_email</span><span class="p">,</span> 
	    <span class="p">);</span> 
	<span class="p">}</span> <span class="k">else</span> <span class="p">{</span> 
	    <span class="c1">// Insert the form data into database or send an email</span> 
            <span class="c1">// Whatever you want to do with these data</span> 
	    
	    <span class="nv">$response_data</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span> 
		<span class="s1">&#39;content&#39;</span> <span class="o">=&gt;</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">render_template</span><span class="p">(</span><span class="s1">&#39;contact&#39;</span><span class="p">),</span> 
		<span class="s1">&#39;type&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;text/html&#39;</span><span class="p">,</span> 
	    <span class="p">);</span> 
	    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">response</span><span class="p">(</span><span class="nv">$response_data</span><span class="p">);</span> 
	<span class="p">}</span> 
	
<span class="p">}</span> 
 
<span class="c1">// End of file: ./application/managers/contact_manager.php </span> 
</pre></div>

<p class="tipbox">
Any native PHP function that accepts one parameter can be used as a rule, like <dfn>htmlspecialchars</dfn>,
<dfn>trim</dfn>,  <dfn>MD5</dfn>, etc.
</p> 

<h2>Changing the Error Delimiters</h2> 
 
<p>By default, the Form Validation class adds a paragraph tag (&lt;p&gt;) around each error message shown. You can either change these delimiters globally or
individually.</p> 
 
<ol> 
 
<li><strong>Changing delimiters Globally</strong> 
 
<p>To globally change the error delimiters, in your controller function, just after loading the Form Validation class, add this:</p> 
 
<code>$this->form_validation->set_error_delimiters('<kbd>&lt;div class="error"></kbd>', '<kbd>&lt;/div></kbd>');</code> 
 
<p>In this example, we've switched to using div tags.</p> 
 
</li> 
 
<li><strong>Changing delimiters Individually</strong> 
 
<p>Each of the two error generating functions shown in this tutorial can be supplied their own delimiters as follows:</p> 
 
<code>&lt;?php echo form_error('field name', '<kbd>&lt;div class="error"></kbd>', '<kbd>&lt;/div></kbd>'); ?></code> 
 
<p>Or:</p> 
 
<code>&lt;?php echo validation_errors('<kbd>&lt;div class="error"></kbd>', '<kbd>&lt;/div></kbd>'); ?></code> 
 
</li> 
</ol> 
 
 
<h2>Showing Errors Individually</h2> 
 
<p>If you prefer to show an error message next to each form field, rather than as a list, you can use the <dfn>form_error()</dfn> function.</p> 
 
<p>Try it! Change your form so that it looks like this:</p> 
 
<textarea class="textarea" style="width:100%" cols="50" rows="18"> 
&lt;h5>Username&lt;/h5>
&lt;?php echo form_error('username'); ?>
&lt;input type="text" name="username" value="&lt;?php echo set_value('username'); ?>" size="50" />
 
&lt;h5>Password&lt;/h5>
&lt;?php echo form_error('password'); ?>
&lt;input type="text" name="password" value="&lt;?php echo set_value('password'); ?>" size="50" />
 
&lt;h5>Password Confirm&lt;/h5>
&lt;?php echo form_error('passconf'); ?>
&lt;input type="text" name="passconf" value="&lt;?php echo set_value('passconf'); ?>" size="50" />
 
&lt;h5>Email Address&lt;/h5>
&lt;?php echo form_error('email'); ?>
&lt;input type="text" name="email" value="&lt;?php echo set_value('email'); ?>" size="50" />
</textarea> 
 
<p>If there are no errors, nothing will be shown.  If there is an error, the message will appear.</p> 
 
<p><strong>Important Note:</strong> If you use an array as the name of a form field, you must supply it as an array to the function.  Example:</p> 
 
<code>&lt;?php echo form_error('<kbd>options[size]</kbd>'); ?><br /> 
&lt;input type="text" name="<kbd>options[size]</kbd>" value="&lt;?php echo set_value("<kbd>options[size]</kbd>"); ?>" size="50" />
</code> 
 
<p>For more info please see the <a href="#arraysasfields">Using Arrays as Field Names</a> section below.</p> 
 

<h2>Rule Reference</h2> 
 
<p>The following is a list of all the native rules that are available to use:</p> 
 
<table class="grid"> 
<tr> 
<th>Rule</th> 
<th>Parameter</th> 
<th>Description</th> 
<th>Example</th> 
</tr><tr> 
 
<td><strong>required</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element is empty.</td> 
<td>&nbsp;</td> 
</tr><tr> 
 
<td><strong>matches</strong></td> 
<td>Yes</td> 
<td>Returns FALSE if the form element does not match the one in the parameter.</td> 
<td>matches[form_item]</td> 
</tr><tr> 
 
<td><strong>min_length</strong></td> 
<td>Yes</td> 
<td>Returns FALSE if the form element is shorter then the parameter value.</td> 
<td>min_length[6]</td> 
</tr><tr> 
 
<td><strong>max_length</strong></td> 
<td>Yes</td> 
<td>Returns FALSE if the form element is longer then the parameter value.</td> 
<td>max_length[12]</td> 
</tr><tr> 
 
<td><strong>exact_length</strong></td> 
<td>Yes</td> 
<td>Returns FALSE if the form element is not exactly the parameter value.</td> 
<td>exact_length[8]</td> 
</tr><tr> 
 
<td><strong>alpha</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than alphabetical characters.</td> 
<td>&nbsp;</td> 
</tr><tr> 
 
<td><strong>alpha_numeric</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than alpha-numeric characters.</td> 
<td>&nbsp;</td> 
</tr><tr> 
 
<td><strong>alpha_dash</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>numeric</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than numeric characters.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>integer</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than an integer.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>is_natural</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>is_natural_no_zero</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>valid_email</strong></td> 
<td>No</td> 
<td>Returns FALSE if the form element does not contain a valid email address.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>valid_emails</strong></td> 
<td>No</td> 
<td>Returns FALSE if any value provided in a comma separated list is not a valid email.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>valid_ip</strong></td> 
<td>No</td> 
<td>Returns FALSE if the supplied IP is not valid.</td> 
<td>&nbsp;</td> 
</tr> 
 
<tr> 
<td><strong>valid_base64</strong></td> 
<td>No</td> 
<td>Returns FALSE if the supplied string contains anything other than valid Base64 characters.</td> 
<td>&nbsp;</td> 
</tr> 
 
 
</table> 
 
<p><strong>Note:</strong> These rules can also be called as discrete functions. For example:</p> 
 
<code>$this->form_validation->required($string);</code> 
 
<p class="notebox">You can also use any native PHP functions that permit one parameter.</p> 

 
</div> 
<!-- end onecolumn -->
