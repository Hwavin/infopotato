<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Security</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Security
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/security/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<div class="box_right greybox">
<blockquote>
<span>A clever person solves a problem. A wise person avoids it.</span>
<div>&mdash; Albert Einstein</div>
</blockquote>
</div>

<h2>Cross-site Scripting Prevention</h2>

<p>
Cross-site scripting (also known as XSS) occurs when a web application gathers malicious data from a user. Often attackers will inject JavaScript, VBScript, ActiveX, HTML, or Flash into a vulnerable application to fool other application users and gather data from them. For example, a poorly design forum system may display user input in forum posts without any checking. An attacker can then inject a piece of malicious JavaScript code into a post so that when other users read this post, the JavaScript runs unexpectedly on their computers.
</p>

<p>
One of the most important measures to prevent XSS attacks is to check user input before displaying them. One can do HTML-encoding with the user input to achieve this goal. However, in some situations, HTML-encoding may not be preferable because it disables all HTML tags.
</p>

<h2>Cross-site Request Forgery Prevention</h2>

<p>
Cross-Site Request Forgery (CSRF) attacks occur when a malicious web site causes a user's web browser to perform an unwanted action on a trusted site. For example, a malicious web site has a page that contains an image tag whose src points to a banking site: http://bank.example/withdraw?transfer=10000&to=someone. If a user who has a login cookie for the banking site happens to visit this malicous page, the action of transferring 10000 dollars to someone will be executed. Contrary to cross-site, which exploits the trust a user has for a particular site, CSRF exploits the trust that a site has for a particular user.
</p>

<p>
To prevent CSRF attacks, it is important to abide to the rule that GET requests should only be allowed to retrieve data rather than modify any data on the server. And for POST requests, they should include some random value which can be recognized by the server to ensure the form is submitted from and the result is sent back to the same origin.
</p>

<h2>Cookie Attack Prevention</h2>

<p>
Protecting cookies from being attacked is of extreme importance, as session IDs are commonly stored in cookies. If one gets hold of a session ID, he essentially owns all relevant session information.
</p>

<p>
There are several countermeasures to prevent cookies from being attacked.
</p>

<ul>
<li>An application can use SSL to create a secure communication channel and only pass the authentication cookie over an HTTPS connection. Attackers are thus unable to decipher the contents in the transferred cookies.</li>
<li>Expire sessions appropriately, including all cookies and session tokens, to reduce the likelihood of being attacked.</li>
<li>Prevent cross-site scripting which causes arbitrary code to run in a user's browser and expose his cookies.</li>
<li>Validate cookie data and detect if they are altered.</li>
</ul>

<h2>Session hijacking, session stealing, session fixation</h2>

<p>
The management session is linked to several types of attacks. The attacker either disposes of his or user session ID and thus gains access to Web applications without knowing the password. Then the application can perform anything without the user knowing. Defense rests in a properly configured server and PHP.
</p>

<p>
InfoPotato configures automatically. The programmer does not have to think about how the session properly secure and can concentrate fully on creating the application.
</p>

<h2>URL attack, control codes, invalid UTF-8</h2>

<p>
Various concepts related to the attacker's attempt to foist your web application malicious input. Consequences can be very diverse, ranging from damage to output XML (eg RSS feeds broken) through the acquisition of sensitive information from a database or passwords. Defense is consistent treatment of all inputs at the level of individual bytes. And honestly, who of you does, and that framework does it?
</p>

<p>
InfoPotato is doing and also fully automatic. You do not set up anything, and all entries will be treated.
</p>
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>

</div> 

