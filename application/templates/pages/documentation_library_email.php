<div class="container"> 

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Email Library</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/library/">Library</a> &gt; Email Library
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/library/email/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>InfoPotato's robust Email Class supports the following features:</p> 
 
<ul> 
<li>Multiple Protocols: Mail, Sendmail, and SMTP</li> 
<li>Multiple recipients</li> 
<li>CC and BCCs</li> 
<li>HTML or Plaintext email</li> 
<li>Attachments</li> 
<li>Word wrapping</li> 
<li>Priorities</li> 
<li>BCC Batch Mode, enabling large email lists to be broken into small BCC batches.</li> 
<li>Email Debugging tools</li> 
</ul> 
 
 
<h2>Sending Email</h2> 
 
<p>Sending email is not only simple, but you can configure it on the fly or set your preferences in a config file.</p> 
 
<p>Here is a basic example demonstrating how you might send email.  Note:  This example assumes you are sending the email from one of your
<a href="../general/controllers.html">controllers</a>.</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;email/email_library&#39;</span><span class="p">,</span> <span class="s1">&#39;email&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span>
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">from</span><span class="p">(</span><span class="s1">&#39;your@example.com&#39;</span><span class="p">,</span> <span class="s1">&#39;Your Name&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">to</span><span class="p">(</span><span class="s1">&#39;someone@example.com&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">cc</span><span class="p">(</span><span class="s1">&#39;another@another-example.com&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">bcc</span><span class="p">(</span><span class="s1">&#39;them@their-example.com&#39;</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">subject</span><span class="p">(</span><span class="s1">&#39;Email Test&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">message</span><span class="p">(</span><span class="s1">&#39;Testing the email class.&#39;</span><span class="p">);</span>	
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">send</span><span class="p">();</span> 
 
<span class="k">echo</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">print_debugger</span><span class="p">();</span> 
</pre></div> 
 
 
 
<h2>Setting Email Preferences</h2> 
 
<p>There are 17 different preferences available to tailor how your email messages are sent. You can either set them manually
as described here, or automatically via preferences stored in your config file, described below:</p> 
 
<p>Preferences are set by passing an array of preference values to the email <dfn>initialize</dfn> function.  Here is an example of how you might set some preferences:</p> 
 
<div class="syntax"><pre>
<span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;protocol&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;sendmail&#39;</span><span class="p">;</span> 
<span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;mailpath&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;/usr/sbin/sendmail&#39;</span><span class="p">;</span> 
<span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;charset&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="s1">&#39;iso-8859-1&#39;</span><span class="p">;</span> 
<span class="nv">$config</span><span class="p">[</span><span class="s1">&#39;wordwrap&#39;</span><span class="p">]</span> <span class="o">=</span> <span class="k">TRUE</span><span class="p">;</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">load_library</span><span class="p">(</span><span class="s1">&#39;SYS&#39;</span><span class="p">,</span> <span class="s1">&#39;email/email_library&#39;</span><span class="p">,</span> <span class="s1">&#39;email&#39;</span><span class="p">,</span> <span class="nv">$config</span><span class="p">);</span>
</pre></div> 

<p><strong>Note:</strong> Most of the preferences have default values that will be used if you do not set them.</p
 
><h3>Setting Email Preferences in a Config File</h3> 
 
<p>If you prefer not to set preferences using the above method, you can instead put them into a config file.
Simply create a new file called the <var>email.php</var>,  add the <var>$config</var> 
array in that file. Then save the file at <var>config/email.php</var> and it will be used automatically. You
will NOT need to use the <dfn>$this->email->initialize()</dfn> function if you save your preferences in a config file.</p> 
 
 
 
 
<h2>Email Preferences</h2> 
 
<p>The following is a list of all the preferences that can be set when sending email.</p> 

<table class="grid"> 
<thead>
<tr><th>Preference</th><th>Default&nbsp;Value</th><th>Options</th><th>Description</th></tr>
</thead>

<tbody>
<tr> 
<td><strong>useragent</strong></td><td class="td">CodeIgniter</td><td class="td">None</td><td class="td">The "user agent".</td> 
</tr><tr> 
<td><strong>protocol</strong></td><td class="td">mail</td><td class="td">mail, sendmail, or smtp</td><td class="td">The mail sending protocol.</td> 
</tr><tr> 
<td><strong>mailpath</strong></td><td class="td">/usr/sbin/sendmail</td><td class="td">None</td><td class="td">The server path to Sendmail.</td> 
</tr><tr> 
<td><strong>smtp_host</strong></td><td class="td">No Default</td><td class="td">None</td><td class="td">SMTP Server Address.</td> 
</tr><tr> 
<td><strong>smtp_user</strong></td><td class="td">No Default</td><td class="td">None</td><td class="td">SMTP Username.</td> 
</tr><tr> 
<td><strong>smtp_pass</strong></td><td class="td">No Default</td><td class="td">None</td><td class="td">SMTP Password.</td> 
</tr><tr> 
<td><strong>smtp_port</strong></td><td class="td">25</td><td class="td">None</td><td class="td">SMTP Port.</td> 
</tr><tr> 
<td><strong>smtp_timeout</strong></td><td class="td">5</td><td class="td">None</td><td class="td">SMTP Timeout (in seconds).</td> 
</tr><tr> 
<td><strong>wordwrap</strong></td><td class="td">TRUE</td><td class="td">TRUE or FALSE (boolean)</td><td class="td">Enable word-wrap.</td> 
</tr><tr> 
<td><strong>wrapchars</strong></td><td class="td">76</td><td class="td"> </td><td class="td">Character count to wrap at.</td> 
</tr><tr> 
<td><strong>mailtype</strong></td><td class="td">text</td><td class="td">text or html</td><td class="td">Type of mail. If you send HTML email you must send it as a complete web page.  Make sure you don't have any relative links or relative image paths otherwise they will not work.</td> 
</tr><tr> 
<td><strong>charset</strong></td><td class="td">utf-8</td><td class="td"></td><td class="td">Character set (utf-8, iso-8859-1, etc.).</td> 
</tr><tr> 
<td><strong>validate</strong></td><td class="td">FALSE</td><td class="td">TRUE or FALSE  (boolean)</td><td class="td">Whether to validate the email address.</td> 
</tr><tr> 
<td><strong>priority</strong></td><td class="td">3</td><td class="td">1, 2, 3, 4, 5</td><td class="td">Email Priority. 1 = highest.  5 = lowest.  3 = normal.</td> 
</tr> 
<tr> 
	<td class="td"><strong>crlf</strong></td> 
	<td class="td">\n</td> 
	<td class="td">&quot;\r\n&quot; or &quot;\n&quot; or &quot;\r&quot;</td> 
	<td class="td">Newline character. (Use &quot;\r\n&quot; to comply with RFC 822).</td> 
</tr> 
<tr> 
<td class="td"><strong>newline</strong></td><td class="td">\n</td> 
<td class="td">"\r\n" or "\n" or &quot;\r&quot;</td><td class="td">Newline character. (Use "\r\n" to comply with RFC 822).</td> 
</tr><tr> 
<td class="td"><strong>bcc_batch_mode</strong></td><td class="td">FALSE</td><td class="td">TRUE or FALSE (boolean)</td><td class="td">Enable BCC Batch Mode.</td> 
</tr><tr> 
<td class="td"><strong>bcc_batch_size</strong></td><td class="td">200</td><td class="td">None</td><td class="td">Number of emails in each BCC batch.</td> 
</tr> 
</tbody>

</table> 
 
 
<h2>Email Function Reference</h2> 
 
<h3>$this->email->from()</h3> 
<p>Sets the email address and name of the person sending the email:</p> 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">from</span><span class="p">(</span><span class="s1">&#39;you@example.com&#39;</span><span class="p">,</span> <span class="s1">&#39;Your Name&#39;</span><span class="p">);</span> 
</pre></div>  
 
<h3>$this->email->reply_to()</h3> 
<p>Sets the reply-to address.  If the information is not provided the information in the "from" function is used. Example:</p> 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">reply_to</span><span class="p">(</span><span class="s1">&#39;you@example.com&#39;</span><span class="p">,</span> <span class="s1">&#39;Your Name&#39;</span><span class="p">);</span> 
</pre></div> 
 
<h3>$this->email->to()</h3> 
<p>Sets the email address(s) of the recipient(s).  Can be a single email, a comma-delimited list or an array:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">to</span><span class="p">(</span><span class="s1">&#39;someone@example.com&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">to</span><span class="p">(</span><span class="s1">&#39;one@example.com, two@example.com, three@example.com&#39;</span><span class="p">);</span> 
<span class="nv">$list</span> <span class="o">=</span> <span class="k">array</span><span class="p">(</span><span class="s1">&#39;one@example.com&#39;</span><span class="p">,</span> <span class="s1">&#39;two@example.com&#39;</span><span class="p">,</span> <span class="s1">&#39;three@example.com&#39;</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">to</span><span class="p">(</span><span class="nv">$list</span><span class="p">);</span> 
</pre></div>

<h3>$this->email->cc()</h3> 
<p>Sets the CC email address(s). Just like the "to", can be a single email, a comma-delimited list or an array.</p> 
 
<h3>$this->email->bcc()</h3> 
<p>Sets the BCC email address(s). Just like the "to", can be a single email, a comma-delimited list or an array.</p> 
 
 
<h3>$this->email->subject()</h3> 
<p>Sets the email subject:</p> 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">subject</span><span class="p">(</span><span class="s1">&#39;This is my subject&#39;</span><span class="p">);</span> 
</pre></div> 

<h3>$this->email->message()</h3> 
<p>Sets the email message body:</p> 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">message</span><span class="p">(</span><span class="s1">&#39;This is my message&#39;</span><span class="p">);</span> 
</pre></div> 

<h3>$this->email->set_alt_message()</h3> 
<p>Sets the alternative email message body:</p> 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">set_alt_message</span><span class="p">(</span><span class="s1">&#39;This is the alternative message&#39;</span><span class="p">);</span> 
</pre></div> 

<p>This is an optional message string which can be used if you send HTML formatted email.  It lets you specify an alternative
message with no HTML formatting which is added to the header string for people who do not accept HTML email.
If you do not set your own message CodeIgniter will extract the message from your HTML email and strip the tags.</p> 
 
 
 
<h3>$this->email->clear()</h3> 
<p>Initializes all the email variables to an empty state.  This function is intended for use if you run the email sending function
in a loop, permitting the data to be reset between cycles.</p> 
<div class="syntax"><pre>
<span class="k">foreach</span> <span class="p">(</span><span class="nv">$list</span> <span class="k">as</span> <span class="nv">$name</span> <span class="o">=&gt;</span> <span class="nv">$address</span><span class="p">)</span> <span class="p">{</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">clear</span><span class="p">();</span> 
 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">to</span><span class="p">(</span><span class="nv">$address</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">from</span><span class="p">(</span><span class="s1">&#39;your@example.com&#39;</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">subject</span><span class="p">(</span><span class="s1">&#39;Here is your info &#39;</span><span class="o">.</span><span class="nv">$name</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">message</span><span class="p">(</span><span class="s1">&#39;Hi &#39;</span><span class="o">.</span><span class="nv">$name</span><span class="o">.</span><span class="s1">&#39; Here is the info you requested.&#39;</span><span class="p">);</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">send</span><span class="p">();</span> 
<span class="p">}</span> 
</pre></div> 
 
<p>If you set the parameter to TRUE any attachments will be cleared as well:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">clear</span><span class="p">(</span><span class="k">TRUE</span><span class="p">);</span> 
</pre></div> 
 
 
<h3>$this->email->send()</h3> 
<p>The Email sending function. Returns boolean TRUE or FALSE based on success or failure, enabling it to be used
conditionally:</p> 
 
<div class="syntax"><pre>
<span class="k">if</span> <span class="p">(</span> <span class="o">!</span> <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">send</span><span class="p">())</span> <span class="p">{</span> 
    <span class="c1">// Generate error</span> 
<span class="p">}</span> 
</pre></div> 
 
 
<h3>$this->email->attach()</h3> 
<p>Enables you to send an attachment. Put the file path/name in the first parameter. Note: Use a file path, not a URL.
For multiple attachments use the function multiple times. For example:</p> 
 
<div class="syntax"><pre>
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">attach</span><span class="p">(</span><span class="s1">&#39;/path/to/photo1.jpg&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">attach</span><span class="p">(</span><span class="s1">&#39;/path/to/photo2.jpg&#39;</span><span class="p">);</span> 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">attach</span><span class="p">(</span><span class="s1">&#39;/path/to/photo3.jpg&#39;</span><span class="p">);</span> 
 
<span class="nv">$this</span><span class="o">-&gt;</span><span class="na">email</span><span class="o">-&gt;</span><span class="na">send</span><span class="p">();</span> 
</pre></div> 
 
<h3>$this->email->print_debugger()</h3> 
<p>Returns a string containing any server messages, the email headers, and the email messsage.  Useful for debugging.</p> 
 
 
<h2>Overriding Word Wrapping</h2> 
 
<p>If you have word wrapping enabled (recommended to comply with RFC 822) and you have a very long link in your email it can
get wrapped too, causing it to become un-clickable by the person receiving it. InfoPotato lets you manually override
word wrapping within part of your message like this:</p> 
 
<div class="syntax"><pre>The text of your email that
gets wrapped normally.
 
{unwrap}http://example.com/a_long_link_that_should_not_be_wrapped.html{/unwrap}
 
More text that will be
wrapped normally.
</pre></div>
<p>Place the item you do not want word-wrapped between: <var>{unwrap}</var> <var>{/unwrap}</var></p> 
<!-- PRINT: stop --> 

<?php echo isset($pager) ? $pager : ''; ?>
 
</div> 

</div>
