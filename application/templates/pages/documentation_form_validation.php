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
 
<ol> 
<li>A form is displayed.</li> 
<li>You fill it in and submit it.</li> 
<li>If you submitted something invalid, or perhaps missed a required item, the form is redisplayed containing your data
along with an error message describing the problem.</li> 
<li>This process continues until you have submitted a valid form.</li> 
</ol> 
 
<p>On the receiving end, the script must:</p> 
 
<ol> 
<li>Check for required data.</li> 
<li>Verify that the data is of the correct type, and meets the correct criteria. For example, if a username is submitted
it must be validated to contain only permitted characters.  It must be of a minimum length,
and not exceed a maximum length. The username can't be someone else's existing username, or perhaps even a reserved word. Etc.</li> 
<li>Sanitize the data for security.</li> 
<li>Pre-format the data if needed (Does the data need to be trimmed?  HTML encoded?  Etc.)</li> 
<li>Prep the data for insertion in the database.</li> 
</ol> 
 
 
<p>Although there is nothing terribly complex about the above process, it usually requires a significant
amount of code, and to display error messages, various control structures are usually placed within the form HTML.
Form validation, while simple to create, is generally very messy and tedious to implement.</p> 
 

<h1>Form Validation Tutorial</h1> 
 
<p>What follows is a "hands on" tutorial for implementing CodeIgniters Form Validation.</p> 
 
 
<p>In order to implement form validation you'll need three things:</p> 
 
<ol> 
<li>A <a href="../general/views.html">View</a> file containing a form.</li> 
<li>A View file containing a "success" message to be displayed upon successful submission.</li> 
<li>A <a href="../general/controllers.html">controller</a> function to receive and process the submitted data.</li> 
</ol> 
 
<p>Let's create those three things, using a member sign-up form as the example.</p> 
 
<h2>The Form</h2> 
 
<p>Using a text editor, create a form called <dfn>myform.php</dfn>.  In it, place this code and save it to your <samp>applications/views/</samp> 
folder:</p> 
 
 
<pre> 
<!-- begin contact --> 
<div id="contact"> 
&lt;?php if (isset($errors)) : ?>
&lt;div class="errorbox">
&lt;?php echo $errors; ?>
&lt;/div>
&lt;?php endif; ?>
 
&lt;form id="contact_form" method="post" action="<?php echo APP_URI_BASE; ?>contact/send_email">  
 
<label for="contact_subject" id="contact_subject_label" class="desc">Subject <span class="req">*</span></label>  
&lt;input type="text" name="contact_subject" id="contact_subject" size="60" value="<?php if (isset($contact_subject)) echo $contact_subject; ?>" class="contact_input" />   
 
<label for="contact_message" id="contact_message_label" class="desc">Message <span class="req">*</span></label>  
&lt;textarea name="contact_message" id="contact_message" class="contact_textarea" rows="8" cols="80"><?php if (isset($contact_message)) echo $contact_message; ?></textarea> 
 
<label for="contact_name" id="contact_name_label" class="desc">Name <span class="req">*</span></label>  
&lt;input type="text" name="contact_name" id="contact_name" size="60" value="<?php if (isset($contact_name)) echo $contact_name; ?>" class="contact_input" />   
 
<label for="contact_email" id="contact_email_label" class="desc">Email <span class="req">*</span></label>  
&lt;input type="text" name="contact_email" id="contact_email" size="60" value="<?php if (isset($contact_email)) echo $contact_email; ?>" class="contact_input" />   
 
 
&lt;input type="submit" name="submit_btn" class="button" id="submit_btn" value="Send" />  
 
&lt;/form>  
 
</div> 
<!-- end contact --> 
</pre> 
 

<h2>The Controller</h2> 
 
<p>Using a text editor, create a controller called <dfn>form.php</dfn>.  In it, place this code and save it to your <samp>applications/controllers/</samp> 
folder:</p> 
 
 
<pre> 
&lt;?php
class Contact_Controller extends Controller {
	public function send_email() {
		// Load Form Validation library
		$this->load_library('form_validation/form_validation_library', 'fv');
 
		$this->fv->set_rules('contact_subject', 'Subject', 'trim|required');
		$this->fv->set_rules('contact_message', 'Message', 'trim|required');
		$this->fv->set_rules('contact_name', 'Name', 'trim|required');
		$this->fv->set_rules('contact_email', 'Email', 'trim|required|valid_email');
		
		$result = $this->fv->run();
		
		$contact_subject = $this->fv->set_value('contact_subject');
		$contact_message = $this->fv->set_value('contact_message');
		$contact_name = $this->fv->set_value('contact_name');
		$contact_email = $this->fv->set_value('contact_email');
 
		if ($result == FALSE) {
			$errors = $this->fv->form_errors();
			
			// Errors and submitted data to be displayed in view
			$data = array(
				'errors' => empty($errors) ? NULL : $errors, 
				'contact_subject' => $contact_subject,
				'contact_message' => $contact_message,
				'contact_name' => $contact_name,
				'contact_email' => $contact_email,
			);
		} else {
			// some code, ex sending email
		}
 
		$layout_data = array(
			'page_title' => 'Contact',
			'content' => $this->view->fetch('pages/contact_content_view', $data),
		);
 
		$response_data = array(
			'content' => $this->view->fetch('layouts/layout_view', $layout_data),
		);
		$this->view->response($response_data);
	}
	
}
 
</pre> 

<h2>Setting Rules Using an Array</h2> 
 
<p>Before moving on it should be noted that the rule setting function can be passed an array if you prefer to set all your rules in one action.
If you use this approach you must name your array keys as indicated:</p> 
 
<code> 
$config = array(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'field'&nbsp;&nbsp;&nbsp;=> 'username', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'label'&nbsp;&nbsp;&nbsp;=> 'Username', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'rules'&nbsp;&nbsp;&nbsp;=> 'required'<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;),<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'field'&nbsp;&nbsp;&nbsp;=> 'password', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'label'&nbsp;&nbsp;&nbsp;=> 'Password', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'rules'&nbsp;&nbsp;&nbsp;=> 'required'<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;),<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'field'&nbsp;&nbsp;&nbsp;=> 'passconf', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'label'&nbsp;&nbsp;&nbsp;=> 'Password Confirmation', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'rules'&nbsp;&nbsp;&nbsp;=> 'required'<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;),&nbsp;&nbsp;&nbsp;<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;array(<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'field'&nbsp;&nbsp;&nbsp;=> 'email', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'label'&nbsp;&nbsp;&nbsp;=> 'Email', <br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'rules'&nbsp;&nbsp;&nbsp;=> 'required'<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;);<br /> 
<br /> 
$this->form_validation->set_rules($config);
</code> 
 
 
<h2>Cascading Rules</h2> 
 
<p>CodeIgniter lets you pipe multiple rules together.  Let's try it. Change your rules in the third parameter of rule setting function, like this:</p> 
 
<code> 
$this->form_validation->set_rules('username', 'Username', 'required|min_length[5]|max_length[12]');<br /> 
$this->form_validation->set_rules('password', 'Password', 'required|matches[passconf]');<br /> 
$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');<br /> 
$this->form_validation->set_rules('email', 'Email', 'required|valid_email');<br /> 
</code> 
 
<p>The above code sets the following rules:</p> 
 
<ol> 
<li>The username field be no shorter than 5 characters and no longer than 12.</li> 
<li>The password field must match the password confirmation field.</li> 
<li>The email field must contain a valid email address.</li> 
</ol> 
 
<p>Give it a try! Submit your form without the proper data and you'll see new error messages that correspond to your new rules.
There are numerous rules available which you can read about in the validation reference.</p> 
 
<h2>Prepping Data</h2> 
 
<p>In addition to the validation functions like the ones we used above, you can also prep your data in various ways.
For example, you can set up rules like this:</p> 
 
<code> 
$this->form_validation->set_rules('username', 'Username', '<kbd>trim</kbd>|required|min_length[5]|max_length[12]|<kbd>xss_clean</kbd>');<br /> 
$this->form_validation->set_rules('password', 'Password', '<kbd>trim</kbd>|required|matches[passconf]|<kbd>md5</kbd>');<br /> 
$this->form_validation->set_rules('passconf', 'Password Confirmation', '<kbd>trim</kbd>|required');<br /> 
$this->form_validation->set_rules('email', 'Email', '<kbd>trim</kbd>|required|valid_email');<br /> 
</code> 
 
 
<p>In the above example, we are "trimming" the fields, converting the password to MD5, and running the username through
the "xss_clean" function, which removes malicious data.</p> 
 
<p><strong>Any native PHP function that accepts one parameter can be used as a rule, like <dfn>htmlspecialchars</dfn>,
<dfn>trim</dfn>,  <dfn>MD5</dfn>, etc.</strong></p> 
 
<p><strong>Note:</strong> You will generally want to use the prepping functions <strong>after</strong> 
the validation rules so if there is an error, the original data will be shown in the form.</p> 
 
<h2>Callbacks: Your own Validation Functions</h2> 
 
<p>The validation system supports callbacks to your own validation functions.  This permits you to extend the validation class
to meet your needs.  For example, if you need to run a database query to see if the user is choosing a unique username, you can
create a callback function that does that.  Let's create a example of this.</p> 
 
<p>In your controller, change the "username" rule to this:</p> 
 
<code>$this->form_validation->set_rules('username', 'Username', '<kbd>callback_username_check</kbd>');</code> 
 
 
<p>Then add a new function called <dfn>username_check</dfn> to your controller.  Here's how your controller should now look:</p> 
 
 
<textarea class="textarea" style="width:100%" cols="50" rows="44">&lt;?php
 
class Form extends Controller {
 
	function index()
	{
		$this->load->helper(array('form', 'url'));
 
		$this->load->library('form_validation');
 
		$this->form_validation->set_rules('username', 'Username', 'callback_username_check');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
 
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('myform');
		}
		else
		{
			$this->load->view('formsuccess');
		}
	}
 
	function username_check($str)
	{
		if ($str == 'test')
		{
			$this->form_validation->set_message('username_check', 'The %s field can not be the word "test"');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
 
}
?></textarea> 
 
<p><dfn>Reload your form and submit it with the word "test" as the username.  You can see that the form field data was passed to your
callback function for you to process.</dfn></p> 
 
<p><strong>To invoke a callback just put the function name in a rule, with "callback_" as the rule prefix.</strong></p> 
 
<p>You can also process the form data that is passed to your callback and return it.  If your callback returns anything other than a boolean TRUE/FALSE
it is assumed that the data is your newly processed form data.</p> 
  
<h2>Setting Error Messages</h2> 
 
 
<p>All of the native error messages are located in the following language file:  <dfn>language/english/form_validation_lang.php</dfn></p> 
 
<p>To set your own custom message you can either edit that file, or use the following function:</p> 
 
<code>$this->form_validation->set_message('<var>rule</var>', '<var>Error Message</var>');</code> 
 
<p>Where <var>rule</var> corresponds to the name of a particular rule, and <var>Error Message</var> is the text you would like displayed.</p> 
 
<p>If you include <dfn>%s</dfn> in your error string, it will be replaced with the "human" name you used for your field when you set your rules.</p> 
 
<p>In the "callback" example above, the error message was set by passing the name of the function:</p> 
 
<code>$this->form_validation->set_message('username_check')</code> 
 
<p>You can also override any error message found in the language file.  For example, to change the message for the "required" rule you will do this:</p> 
 
<code>$this->form_validation->set_message('required', 'Your custom message here');</code> 
 
<h2>Translating Field Names</h2> 
 
<p>If you would like to store the "human" name you passed to the <dfn>set_rules()</dfn> function in a language file, and therefore make the name able to be translated, here's how:</p> 
 
<p>First, prefix your "human" name with <dfn>lang:</dfn>, as in this example:</p> 
 
<code> 
$this->form_validation->set_rules('first_name', '<kbd>lang:</kbd>first_name', 'required');<br /> 
</code> 
 
<p>Then, store the name in one of your language file arrays (without the prefix):</p> 
 
<code>$lang['first_name'] = 'First Name';</code> 
 
<p><strong>Note:</strong> If you store your array item in a language file that is not loaded automatically by CI, you'll need to remember to load it in your controller using:</p> 
 
<code>$this->lang->load('file_name');</code> 
 
<p>See the <a href="language.html">Language Class</a> page for more info regarding language files.</p> 
 

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
 

<h1>Using Arrays as Field Names</h1> 
 
<p>The Form Validation class supports the use of arrays as field names.  Consider this example:</p> 
 
<code>&lt;input type="text" name="<kbd>options[]</kbd>" value="" size="50" /></code> 
 
<p>If you do use an array as a field name, you must use the EXACT array name in the <a href="#helperreference">Helper Functions</a> that require the field name,
and as your Validation Rule field name.</p> 
 
<p>For example, to set a rule for the above field you would use:</p> 
 
<code>$this->form_validation->set_rules('<kbd>options[]</kbd>', 'Options', 'required');</code> 
 
<p>Or, to show an error for the above field you would use:</p> 
 
<code>&lt;?php echo form_error('<kbd>options[]</kbd>'); ?></code> 
 
<p>Or to re-populate the field you would use:</p> 
 
<code>&lt;input type="text" name="<kbd>options[]</kbd>" value="<kbd>&lt;?php echo set_value('<kbd>options[]</kbd>'); ?></kbd>" size="50" /></code> 
 
<p>You can use multidimensional arrays as field names as well. For example:</p> 
 
<code>&lt;input type="text" name="<kbd>options[size]</kbd>" value="" size="50" /></code> 
 
<p>Or even:</p> 
 
<code>&lt;input type="text" name="<kbd>sports[nba][basketball]</kbd>" value="" size="50" /></code> 
 
<p>As with our first example, you must use the exact array name in the helper functions:</p> 
 
<code>&lt;?php echo form_error('<kbd>sports[nba][basketball]</kbd>'); ?></code> 
 
<p>If you are using checkboxes (or other fields) that have multiple options, don't forget to leave an empty bracket after each option, so that all selections will be added to the
POST array:</p> 
 
<code> 
&lt;input type="checkbox" name="<kbd>options[]</kbd>" value="red" /><br /> 
&lt;input type="checkbox" name="<kbd>options[]</kbd>" value="blue" /><br /> 
&lt;input type="checkbox" name="<kbd>options[]</kbd>" value="green" />
</code> 
 
<p>Or if you use a multidimensional array:</p> 
 
<code> 
&lt;input type="checkbox" name="<kbd>options[color][]</kbd>" value="red" /><br /> 
&lt;input type="checkbox" name="<kbd>options[color][]</kbd>" value="blue" /><br /> 
&lt;input type="checkbox" name="<kbd>options[color][]</kbd>" value="green" />
</code> 
 
<p>When you use a helper function you'll include the bracket as well:</p> 
 
<code>&lt;?php echo form_error('<kbd>options[color][]</kbd>'); ?></code> 
 
<h1>Rule Reference</h1> 
 
<p>The following is a list of all the native rules that are available to use:</p> 
 
 
 
<table class="grid"> 
<tr> 
<th>Rule</th> 
<th>Parameter</th> 
<th>Description</th> 
<th>Example</th> 
</tr><tr> 
 
<td class="td"><strong>required</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element is empty.</td> 
<td class="td">&nbsp;</td> 
</tr><tr> 
 
<td class="td"><strong>matches</strong></td> 
<td class="td">Yes</td> 
<td class="td">Returns FALSE if the form element does not match the one in the parameter.</td> 
<td class="td">matches[form_item]</td> 
</tr><tr> 
 
<td class="td"><strong>min_length</strong></td> 
<td class="td">Yes</td> 
<td class="td">Returns FALSE if the form element is shorter then the parameter value.</td> 
<td class="td">min_length[6]</td> 
</tr><tr> 
 
<td class="td"><strong>max_length</strong></td> 
<td class="td">Yes</td> 
<td class="td">Returns FALSE if the form element is longer then the parameter value.</td> 
<td class="td">max_length[12]</td> 
</tr><tr> 
 
<td class="td"><strong>exact_length</strong></td> 
<td class="td">Yes</td> 
<td class="td">Returns FALSE if the form element is not exactly the parameter value.</td> 
<td class="td">exact_length[8]</td> 
</tr><tr> 
 
<td class="td"><strong>alpha</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than alphabetical characters.</td> 
<td class="td">&nbsp;</td> 
</tr><tr> 
 
<td class="td"><strong>alpha_numeric</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than alpha-numeric characters.</td> 
<td class="td">&nbsp;</td> 
</tr><tr> 
 
<td class="td"><strong>alpha_dash</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than alpha-numeric characters, underscores or dashes.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>numeric</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than numeric characters.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>integer</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than an integer.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>is_natural</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than a natural number: 0, 1, 2, 3, etc.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>is_natural_no_zero</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element contains anything other than a natural number, but not zero: 1, 2, 3, etc.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>valid_email</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the form element does not contain a valid email address.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>valid_emails</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if any value provided in a comma separated list is not a valid email.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>valid_ip</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the supplied IP is not valid.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
<tr> 
<td class="td"><strong>valid_base64</strong></td> 
<td class="td">No</td> 
<td class="td">Returns FALSE if the supplied string contains anything other than valid Base64 characters.</td> 
<td class="td">&nbsp;</td> 
</tr> 
 
 
</table> 
 
<p><strong>Note:</strong> These rules can also be called as discrete functions. For example:</p> 
 
<code>$this->form_validation->required($string);</code> 
 
<p class="important"><strong>Note:</strong> You can also use any native PHP functions that permit one parameter.</p> 
 

<h1>Prepping Reference</h1> 
 
<p>The following is a list of all the prepping functions that are available to use:</p> 
 
 
 
<table class="grid"> 
<tr> 
<th>Name</th> 
<th>Parameter</th> 
<th>Description</th> 
</tr><tr> 
 
<td class="td"><strong>xss_clean</strong></td> 
<td class="td">No</td> 
<td class="td">Runs the data through the XSS filtering function, described in the <a href="input.html">Input Class</a> page.</td> 
</tr><tr> 
 
<td class="td"><strong>prep_for_form</strong></td> 
<td class="td">No</td> 
<td class="td">Converts special characters so that HTML data can be shown in a form field without breaking it.</td> 
</tr><tr> 
 
<td class="td"><strong>prep_url</strong></td> 
<td class="td">No</td> 
<td class="td">Adds "http://" to URLs if missing.</td> 
</tr><tr> 
 
<td class="td"><strong>strip_image_tags</strong></td> 
<td class="td">No</td> 
<td class="td">Strips the HTML from image tags leaving the raw URL.</td> 
</tr><tr> 
 
<td class="td"><strong>encode_php_tags</strong></td> 
<td class="td">No</td> 
<td class="td">Converts PHP tags to entities.</td> 
</tr> 
 
</table> 
 
<p class="important"><strong>Note:</strong> You can also use any native PHP functions that permit one parameter,
like <kbd>trim</kbd>, <kbd>htmlspecialchars</kbd>, <kbd>urldecode</kbd>, etc.</p> 
 

<h1>Function Reference</h1> 
 
<p>The following functions are intended for use in your controller functions.</p> 
 
<h2>$this->form_validation->set_rules();</h2> 
 
<p>Permits you to set validation rules, as described in the tutorial sections above:</p> 
 
<ul> 
<li><a href="#validationrules">Setting Validation Rules</a></li> 
<li><a href="#savingtoconfig">Saving Groups of Validation Rules to a Config File</a></li> 
</ul> 
 
 
<h2>$this->form_validation->run();</h2> 
 
<p>Runs the validation routines.  Returns boolean TRUE on success and FALSE on failure. You can optionally pass the name of the validation
group via the function, as described in: <a href="#savingtoconfig">Saving Groups of Validation Rules to a Config File</a>.</p> 
 
 
<h2>$this->form_validation->set_message();</h2> 
 
<p>Permits you to set custom error messages.  See <a href="#settingerrors">Setting Error Messages</a> above.</p> 
 

<h1>Helper Reference</h1> 
 
<p>The following helper functions are available for use in the view files containing your forms.  Note that these are procedural functions, so they
<strong>do not</strong> require you to prepend them with $this->form_validation.</p> 
 
<h2>form_error()</h2> 
 
<p>Shows an individual error message associated with the field name supplied to the function.  Example:</p> 
 
<code>&lt;?php echo form_error('username'); ?></code> 
 
<p>The error delimiters can be optionally specified.  See the <a href="#errordelimiters">Changing the Error Delimiters</a> section above.</p> 
 
 
 
<h2>validation_errors()</h2> 
<p>Shows all error messages as a string:  Example:</p> 
 
<code>&lt;?php echo validation_errors(); ?></code> 
 
<p>The error delimiters can be optionally specified.  See the <a href="#errordelimiters">Changing the Error Delimiters</a> section above.</p> 
 
 
 
<h2>set_value()</h2> 
 
<p>Permits you to set the value of an input form or textarea. You must supply the field name via the first parameter of the function.
The second (optional) parameter allows you to set a default value for the form. Example:</p> 
 
<code>&lt;input type="text" name="quantity" value="<dfn>&lt;?php echo set_value('quantity', '0'); ?></dfn>" size="50" /></code> 
 
<p>The above form will show "0" when loaded for the first time.</p> 
 
<h2>set_select()</h2> 
 
<p>If you use a <dfn>&lt;select></dfn> menu, this function permits you to display the menu item that was selected.  The first parameter
must contain the name of the select menu, the second parameter must contain the value of
each item, and the third (optional) parameter lets you set an item as the default (use boolean TRUE/FALSE).</p> 
 
<p>Example:</p> 
 
<code> 
&lt;select name="myselect"><br /> 
&lt;option value="one" <dfn>&lt;?php echo  set_select('myselect', 'one', TRUE); ?></dfn> >One&lt;/option><br /> 
&lt;option value="two" <dfn>&lt;?php echo  set_select('myselect', 'two'); ?></dfn> >Two&lt;/option><br /> 
&lt;option value="three" <dfn>&lt;?php echo  set_select('myselect', 'three'); ?></dfn> >Three&lt;/option><br /> 
&lt;/select>
</code> 
 
 
<h2>set_checkbox()</h2> 
 
<p>Permits you to display a checkbox in the state it was submitted.  The first parameter
must contain the name of the checkbox, the second parameter must contain its value, and the third (optional) parameter lets you set an item as the default (use boolean TRUE/FALSE). Example:</p> 
 
<code>&lt;input type="checkbox" name="mycheck[]" value="1" <dfn>&lt;?php echo set_checkbox('mycheck[]', '1'); ?></dfn> /><br /> 
&lt;input type="checkbox" name="mycheck[]" value="2" <dfn>&lt;?php echo set_checkbox('mycheck[]', '2'); ?></dfn> /></code> 
 
 
<h2>set_radio()</h2> 
 
<p>Permits you to display radio buttons in the state they were submitted. This function is identical to the <strong>set_checkbox()</strong> function above.</p> 
 
<code>&lt;input type="radio" name="myradio" value="1" <dfn>&lt;?php echo  set_radio('myradio', '1', TRUE); ?></dfn> /><br /> 
&lt;input type="radio" name="myradio" value="2" <dfn>&lt;?php echo  set_radio('myradio', '2'); ?></dfn> /></code> 
 
 
</div> 
<!-- end onecolumn -->
