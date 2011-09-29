<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">User Agent Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; User Agent Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/user_agent/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>The User Agent Class provides functions that help identify information about the browser, mobile device, or robot visiting your site.
In addition you can get referrer information as well as language and supported character-set information.</p> 
 
<h2>Initializing the Class</h2> 
 
<p>Like most other classes in CodeIgniter, the User Agent class is initialized in your controller using the <dfn>$this->load->library</dfn> function:</p> 
 
<code>$this->load->library('user_agent');</code> 
<p>Once loaded, the object will be available using: <dfn>$this->agent</dfn></p> 
 
<h2>User Agent Definitions</h2> 
 
<p>The user agent name definitions are located in a config file located at: <dfn>application/config/user_agents.php</dfn>.  You may add items to the
various user agent arrays if needed.</p> 
 
<h2>Example</h2> 
 
<p>When the User Agent class is initialized it will attempt to determine whether the user agent browsing your site is
a web browser, a mobile device, or a robot.  It will also gather the platform information if it is available.</p> 
 
 
<code> 
$this->load->library('user_agent');<br /> 
<br /> 
if ($this->agent->is_browser())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$agent  = $this->agent->browser().' '.$this->agent->version();<br /> 
}<br /> 
elseif ($this->agent->is_robot())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$agent = $this->agent->robot();<br /> 
}<br /> 
elseif ($this->agent->is_mobile())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$agent = $this->agent->mobile();<br /> 
}<br /> 
else<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$agent = 'Unidentified User Agent';<br /> 
}<br /> 
<br /> 
echo $agent;<br /> 
<br /> 
echo $this->agent->platform(); // Platform info (Windows, Linux, Mac, etc.)
</code> 
 
 
<h1>Function Reference</h1> 
 
<h2>$this->agent->is_browser()</h2> 
<p>Returns TRUE/FALSE (boolean) if the user agent is a known web browser.</p> 
 
<code> if ($this->agent->is_browser('Safari'))<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;echo 'You are using Safari.';<br /> 
}<br /> 
else if ($this->agent->is_browser())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;echo 'You are using a browser.';<br /> 
}</code> 
 
<p class="notebox"><strong>Note:</strong>&nbsp; The string "Safari" in this example is an array key in the list of browser definitions.
You can find this list in <dfn>application/config/user_agents.php</dfn> if you want to add new browsers or change the stings.</p> 
 
<h2>$this->agent->is_mobile()</h2> 
<p>Returns TRUE/FALSE (boolean) if the user agent is a known mobile device.</p> 
 
<code> if ($this->agent->is_mobile('iphone'))<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$this->load->view('iphone/home');<br /> 
}<br /> 
else if ($this->agent->is_mobile())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$this->load->view('mobile/home');<br /> 
}<br/> 
else<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;$this->load->view('web/home');<br /> 
}</code> 
 
<h2>$this->agent->is_robot()</h2> 
<p>Returns TRUE/FALSE (boolean) if the user agent is a known robot.</p> 
 
<p class="notebox"><strong>Note:</strong>&nbsp; The user agent library only contains the most common robot
definitions.  It is not a complete list of bots. There are hundreds of them so searching for each one would not be
very efficient. If you find that some bots that commonly visit your site are missing from the list you can add them to your
<dfn>application/config/user_agents.php</dfn> file.</p> 
 
<h2>$this->agent->is_referral()</h2> 
<p>Returns TRUE/FALSE (boolean) if the user agent was referred from another site.</p> 
 
 
<h2>$this->agent->browser()</h2> 
<p>Returns a string containing the name of the web browser viewing your site.</p> 
 
<h2>$this->agent->version()</h2> 
<p>Returns a string containing the version number of the web browser viewing your site.</p> 
 
<h2>$this->agent->mobile()</h2> 
<p>Returns a string containing the name of the mobile device viewing your site.</p> 
 
<h2>$this->agent->robot()</h2> 
<p>Returns a string containing the name of the robot viewing your site.</p> 
 
<h2>$this->agent->platform()</h2> 
<p>Returns a string containing the platform viewing your site (Linux, Windows, OS X, etc.).</p> 
 
<h2>$this->agent->referrer()</h2> 
<p>The referrer, if the user agent was referred from another site. Typically you'll test for this as follows:</p> 
 
<code> if ($this->agent->is_referral())<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;echo $this->agent->referrer();<br /> 
}</code> 
 
 
<h2>$this->agent->agent_string()</h2> 
<p>Returns a string containing the full user agent string.  Typically it will be something like this:</p> 
 
<code>Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.0.4) Gecko/20060613 Camino/1.0.2</code> 
 
 
<h2>$this->agent->accept_lang()</h2> 
<p>Lets you determine if the user agent accepts a particular language. Example:</p> 
 
<code>if ($this->agent->accept_lang('en'))<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;echo 'You accept English!';<br /> 
}</code> 
 
<p class="notebox"><strong>Note:</strong> This function is not typically very reliable
since some browsers do not provide language info, and even among those that do, it is not always accurate. </p> 
 
 
 
<h2>$this->agent->accept_charset()</h2> 
<p>Lets you determine if the user agent accepts a particular character set. Example:</p> 
 
<code>if ($this->agent->accept_charset('utf-8'))<br /> 
{<br /> 
&nbsp;&nbsp;&nbsp;&nbsp;echo 'You browser supports UTF-8!';<br /> 
}</code> 
 
<p class="notebox">
<strong>Note:</strong> This function is not typically very reliable
since some browsers do not provide character-set info, and even among those that do, it is not always accurate. 
</p> 
<!-- PRINT: stop --> 

<?php echo isset($pager) ? $pager : ''; ?>
 
</div> 

