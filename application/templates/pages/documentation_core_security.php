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

<h2>PHP Superglobals Security Considerations</h2>

<p>
The PHP Superglobals are a handful of arrays that provide to a PHP script global access to data originating externally. Whereas PHP scripts contain variables that are local to that script and functions may have variables that are only accessible within that function, the PHP Superglobals represent data coming from URLs, HTML forms, cookies, sessions, and the Web server itself. $HTTP_GET_VARS, $HTTP_POST_VARS, etc., served these same purposes but the PHP superglobal variables are better in that they can also be accessed within any functions (i.e., they have global scope).
</p>

<p>
Introduced in PHP 4.1, the Superglobals are:
</p>

<ul>
<li><strong>$_GET</strong>The $_GET Superglobal represents data sent to the PHP script in a URL. This applies both to directly accessed URLs (e.g., http://www.example.com/page.php?id=2) and form submissions that use the GET method.</li>
<li><strong>$_POST</strong>The $_POST Superglobal represents data sent to the PHP script via HTTP POST. This is normally a form with a method of POST.</li>
<li><strong>$_COOKIE</strong>The $_COOKIE Superglobal represents data available to a PHP script via HTTP cookies.</li>
<li><strong>$_REQUEST</strong>The $_REQUEST Superglobal is a combination of $_GET, $_POST, and $_COOKIE.</li>
<li><strong>$_SESSION</strong>The $_SESSION Superglobal represents data available to a PHP script that has previously been stored in a session.</li>
<li><strong>$_SERVER</strong>The $_SERVER Superglobal represents data available to a PHP script from the Web server itself. Common uses of $_SERVER is to refer to the current PHP script ($_SERVER['PHP_SELF']), the path on the server to that script, the host name, and so on.</li>
<li><strong>$_ENV</strong>The $_ENV Superglobal represents data available to a PHP script from the environment in which PHP is running.</li>
<li><strong>$_FILES</strong>The $_FILES Superglobal represents data available to a PHP script from HTTP POST file uploads. Using $_FILES is the currently preferred way to handle uploaded files in PHP.</li>
</ul>

<p>
Another PHP Superglobal, called $GLOBALS, stores every variable with global scope, which includes the above. Unlike the other Superglobals, $GLOBALS has been around since PHP 3.
</p>

<p>
One key aspect of Web application security is referring to variables with precision, which is why relying upon register_globals is bad. For the same reason, one should not use $_REQUEST as it is less exact, and therefore less secure, than explicitly referring to $_GET, $_POST, or $_COOKIE. Furthermore, the order in which the GET, POST, and COOKIE data is loaded into the $_REQUEST array is dictated by PHP's variables_orders configuration, so the same reference to $_REQUEST in a PHP script could behave differently on different servers.
</p>

<h2>Cross-site Scripting Prevention</h2>

<p>
Cross-site scripting (also known as XSS) occurs when a web application gathers malicious data from a user. Often attackers will inject JavaScript, VBScript, ActiveX, HTML, or Flash into a vulnerable application to fool other application users and gather data from them. For example, a poorly design forum system may display user input in forum posts without any checking. An attacker can then inject a piece of malicious JavaScript code into a post so that when other users read this post, the JavaScript runs unexpectedly on their computers.
</p>

<p>
One of the most important measures to prevent XSS attacks is to check user input before displaying them. One can do HTML-encoding with the user input to achieve this goal. However, in some situations, HTML-encoding may not be preferable because it disables all HTML tags.
</p>

<div class="notebox">
<a href="http://ha.ckers.org/xss.html" class="external_link">XSS (Cross Site Scripting) Cheat Sheet (Esp: for filter evasion)</a>
</div>

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