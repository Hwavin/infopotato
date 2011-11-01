<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Session</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Session
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/session/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Your application has a session for each user in which you can store small amounts of data that will be persisted between requests. The session is only available in the mamager and the template and can use one of a number of different storage mechanisms:
</p>

<ul>
<li>CookieStore – Stores everything on the client.</li>
<li>DRbStore – Stores the data on a DRb server.</li>
<li>MemCacheStore – Stores the data in a memcache.</li>
</ul>

<p class="notebox">
All session stores use a cookie to store a unique ID for each session (you must use a cookie, InfoPotato will not allow you to pass the session ID in the URI as this is less secure).
</p>

<p>
For most stores this ID is used to look up the session data on the server, e.g. in a database table. There is one exception, and that is the default and recommended session store – the CookieStore – which stores all session data in the cookie itself (the ID is still available to you if you need it). This has the advantage of being very lightweight and it requires zero setup in a new application in order to use the session. The cookie data is cryptographically signed to make it tamper-proof, but it is not encrypted, so anyone with access to it can read its contents but not edit it (InfoPotato will not accept it if it has been edited).
</p>

<p>
The CookieStore can store around 4kB of data — much less than the others — but this is usually enough. Storing large amounts of data in the session is discouraged no matter which session store your application uses. You should especially avoid storing complex objects (anything other than basic objects, the most common example being model instances) in the session, as the server might not be able to reassemble them between requests, which will result in an error.
</p>

<p> 
The Session class provides an enhanced interface to PHP's native session handling and $_SESSION superglobal features.
</p> 
 
<h2>Configuration (Security)</h2> 
 
<p> 
There are three options for configuring the session, the set_path(), set_length() and ignore_subdomain() static methods. All must be called before any other Session methods.
</p> 
 
<h2>Session File Path</h2> 
 
<p> 
The most important method to call when setting up a site is set_path(). This static method accepts a single parameter, the $directory to save all session files in. The directory specified must be writable by the web server, and should not contain anything except for session files because the session manager will delete old files after the predetermined session time has expired.
</p> 
 
<p> 
By default, all sites on a server use the same temporary directory to store the session files. This opens the opportunity for cross-site session transfer since a valid session ID can be pasted from from one session cookie to another. By setting the session directory per site, this type of attack is prevented. For additional security, it is wise to set the session directory to a location that is not readable by other users so they can not find it and set their session directory to the same place.
</p> 
 
<div class="syntax"><pre>
<span class="nx">Session</span><span class="o">::</span><span class="na">set_path</span><span class="p">(</span><span class="s1">&#39;/path/to/private/writable/dir&#39;</span><span class="p">);</span> 
</pre></div>
 
<h2>Duration</h2> 
 
<p> 
The static method setLength() allows you to set the minimum length of the session, using English descriptions of a timespan. Note that the minimum length, not the exact length, is specified since the session garbage collector uses a probabilistic approach to cleaning up session data. If the session length is set, the session directory should also be set via setPath() or else other sites on the server may delete session files that they consider "old", but that have not expired for the current site.
</p> 
 
<p> 
Here are a few example of setting the session length:
</p> 
 
<div class="syntax"><pre>
<span class="nx">Session</span><span class="o">::</span><span class="na">set_length</span><span class="p">(</span><span class="s1">&#39;30 minutes&#39;</span><span class="p">);</span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">set_length</span><span class="p">(</span><span class="s1">&#39;1 hour&#39;</span><span class="p">);</span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">set_length</span><span class="p">(</span><span class="s1">&#39;1 day 2 hours&#39;</span><span class="p">);</span> 
</pre></div> 
 
<p class="important">There is a second, optional, parameter $persistent_timespan which is discussed in the section Keeping Users Logged In.</p> 
 
<h2>Spanning Subdomains</h2> 
 
<p> 
By default PHP will only allow access to the $_SESSION superglobal values by pages on the same subdomain, such that www.example.com could access the session, but example.com could not. Calling ignoreSubdomain() removes that restriction and allows access to any subdomain.
</p> 
 
<h2>Preventing Session Fixation (Security)</h2> 
 
<p> 
Session fixation is an exploit where an attacker provides a user with a known session id and then uses the same session id to access their authentication session once they have logged in. Below is a simple example of a URL that allows the attacker to know the user's session id:
</p> 
 
<div class="syntax">
http://example.com/login.php?PHPSESSID=abcdef1234567890
</div> 
 
<p> 
After the unsuspecting user has logged into the site, the attacker simply needs to set the same session id in his browser and he'll have full access to the user's session and information.
</p> 
 
<p> 
The Session class prevents against such session fixation attacks by automatically setting the session.use_cookies and session.use_only_cookies ini settings so that session ids will not be accepted in a query string or POST data, but only in cookies.
</p> 
 
<p> 
When using the fAuthorization class, an additional layer of protected is added because all operations that add user information to the session will automatically regenerate the session id. This way even if an attacker was able to influence the session id, it will change once any useful information is present.
</p> 
 
<h2>Controlling the Session</h2> 
 
<p> 
A session can be in one of three states: open, closed, and non-existent. An open session can have data written to the $_SESSION superglobal. A closed session retains all information, however the information can not be read or written. A non-existent session is exactly that, not present at all.
</p> 
 
<h2>Opening</h2> 
 
<p> 
The session is automatically opened when any session method such as set(), get() or destroy() is called. It can also be opened explicitly by calling the static method open(). In the case that a Cannot send session cache limiter - headers already sent warning is generated, be sure to call open() before any output is sent to the browser.
</p> 
 
<div class="syntax"><pre>
<span class="c1">// If you aren&#39;t using the session until after content has been output, </span> 
<span class="c1">// be sure to explicitly open the session before any content is echoed </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">open</span><span class="p">();</span> 
</pre></div> 
 
<h2>Closing</h2> 
 
<p> 
To close the session, simply call close(). The session information can be erased by calling destroy().
</p> 
 
<p> 
During normal usage of (see Storing and Retrieving Values for details) you can read and write throughout the script or page. However, if close() has been called on a page, no data can be read from or written to the session cache after that point.
</p> 
 
<p> 
There is, however, some benefit to closing the session once you are done, rather than waiting for the page to finish execution and the session to be closed automatically. The biggest limitation of PHP is that only a single page can be reading from or writing to a single session. This means a user with multiple browsers or tabs open to a site will only be able to load data from one page at a time. Any other pages being requested that need session data will have to wait until the first page is complete. On most sites with fast-loading pages this may not be an issue, however if any pages take any significant amount of time to the load, users may notice the site will become unresponsive.
</p> 
 
<div class="syntax"><pre>
<span class="c1">// If you are about to execute a time-intensive block of code and no longer need the seesion, close it</span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">close</span><span class="p">();</span> 
</pre></div> 
 
<h2>Destroying</h2> 
 
<p> 
Finally, the destroy() method will completely erase all data from a session and destroy the session id, preventing it from being opened again. This method is most useful when a logged-in user logs out.
</p> 
 
<div class="syntax"><pre>
<span class="c1">// If a user is logging out, remove the information you have stored about them&lt;br /&gt; </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">destroy</span><span class="p">();</span> 
</pre></div>
 
<h2>Keeping Users Logged In</h2> 
 
<p> 
On most sites that have a user login system, it will often be desirable to provide an option for a user to stay logged in even after their browser closes. Obviously this can be a security issue, however many large websites control the functionality via a checkbox in the login form that is labelled "Keep me logged in." This will usually keep a user logged in for a week or two.
</p> 
 
<p> 
To implement this is Flourish, the static method $this->s->set_length() allows for an optional second parameter, $persistent_timespan, which enables persistent logins and sets their length. Whenever using this functionality please be sure to set a custom session file path.
</p> 
 
<div class="syntax"><pre>
<span class="nx">Session</span><span class="o">::</span><span class="na">set_length</span><span class="p">(</span><span class="s1">&#39;30 minutes&#39;</span><span class="p">,</span> <span class="s1">&#39;1 week&#39;</span><span class="p">);</span> 
</pre></div> 
 
<p> 
This will not cause all users to stay logged in for a week. The session files will only be garbage collected after a week, but fSession uses a timestamp in the session superglobal to log normal sessions out after 30 minutes.
</p> 
 
<p> 
To enable a user to stay logged in for the whole $persistent_timespan and to stay logged in across browser restarts, the static method $this->s->enable_persistence() must be called when they log in. Here is an example:
</p> 
 
 
<p>Please note that set_length() must be called before enable_persistence().</p> 
 
<h2>Storing and Retrieving Values</h2> 
 
<p> 
Now that we have discussed how to control the session, let's get into the heart of the matter, storing and retrieving values. There are two methods available to accomplish this task, set() and get().
</p> 
 
<p> 
The set() method takes two parameters, $key and $value. In a fairly straight-forward manner, $key specifies what identifier to save the $value under.
</p> 
 
<p> 
The default prefix is 'Session::'. It is recommended that under normal use the prefix is not changed. A logical place to change the prefix would be for values specific to another class. For example, the fAuthorization class changes the prefix to that all authorization-related session data does not conflict with anything a developer may add.
</p> 
 
<p>Here are some examples of adding data to the session:</p> 
 
<div class="syntax"><pre>
<span class="c1">// This is equivalent to $_SESSION[&#39;Session::current_user_id&#39;] = 5; </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;current_user_id&#39;</span><span class="p">,</span> <span class="m">5</span><span class="p">);</span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;last_viewed_article&#39;</span><span class="p">,</span> <span class="m">42</span><span class="p">);</span> 
 
<span class="c1">// Using the prefix here could allow us to not worry about overwriting values </span> 
<span class="c1">// This is equivalent to $_SESSION[&#39;forum::current_user_id&#39;] = 2; </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span><span class="s1">&#39;current_user_id&#39;</span><span class="p">,</span> <span class="m">2</span><span class="p">,</span> <span class="s1">&#39;forum::&#39;</span><span class="p">);</span> 
</pre></div> 
 
<p> 
Hand-in-hand with the set() method is get(). get() allows retrieval of session values with a twist. The first parameter, $key specifies what value to retrieve. The second (optional) parameter is $default_value. This value will be returned if the requested $key has no value set. Here are some example of getting values out of the session:
</p> 
 
<div class="syntax"><pre>
<span class="nv">$current_user_id</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;current_user_id&#39;</span><span class="p">);</span> 
<span class="nv">$user_groups</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;user_groups&#39;</span><span class="p">,</span> <span class="k">array</span><span class="p">(</span><span class="m">1</span><span class="p">,</span><span class="m">2</span><span class="p">));</span>  
</pre></div> 
 
<h2>Deleting Values</h2> 
 
<p> 
If you wish to unset a session value, simply use the delete() method. It accepts the name of the $key and returns the value:
</p> 
 
<div class="syntax"><pre>
<span class="nv">$name</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">delete</span><span class="p">(</span><span class="s1">&#39;name&#39;</span><span class="p">);</span>  
</pre></div>
 
<p>An optional second parameter allows providing a $default_value to be returned if the key specified is not set.</p> 

<div class="syntax"><pre>
<span class="nv">$name</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">delete</span><span class="p">(</span><span class="s1">&#39;name&#39;</span><span class="p">,</span> <span class="s1">&#39;No name specified&#39;</span><span class="p">);</span>   
</pre></div> 
 
<p>To delete all keys for a specific prefix, use the clear() method:</p> 
 
<div class="syntax"><pre>
<span class="c1">// Clear the default fSession:: prefix </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">clear</span><span class="p">();</span> 
 
<span class="c1">// Clear all keys that start with MyPrefix_ </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">clear</span><span class="p">(</span><span class="s1">&#39;MyPrefix_&#39;</span><span class="p">);</span>   
</pre></div>
 
<h2>Adding and Removing Values</h2> 
 
<p> 
The static methods add() and remove() allow adding and removing values from arrays stored in the session. add() accepts a $key and the $value to add. If the key is not an array, an array will be created and the new value will be added.
</p> 
 
<div class="syntax"><pre>
<span class="c1">// Add John Smith at the end of users </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">add</span><span class="p">(</span><span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="s1">&#39;John Smith&#39;</span><span class="p">);</span>   
</pre></div> 
 
<p>The new value will be added at the end of the array unless the optional third parameter, $beginning, is set to TRUE.</p> 
 
<div class="syntax"><pre>
<span class="c1">// Add Jane Smith at the beginning of users </span> 
<span class="nx">Session</span><span class="o">::</span><span class="na">add</span><span class="p">(</span><span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="s1">&#39;Jane Smith&#39;</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span>   
</pre></div> 
 
<p>remove() accepts one parameter, the $key to remove a value from, and returns the removed value. The value will be removed from the end of the array unless the second optional parameter, $beginning, is set to TRUE.</p> 
 
<div class="syntax"><pre>
<span class="nv">$last_value</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;users&#39;</span><span class="p">);</span> 
<span class="nv">$first_value</span> <span class="o">=</span> <span class="nx">Session</span><span class="o">::</span><span class="na">remove</span><span class="p">(</span><span class="s1">&#39;users&#39;</span><span class="p">,</span> <span class="k">TRUE</span><span class="p">);</span> 
</pre></div> 
 
<h2>Array Dereferencing</h2> 
 
<p> 
When a value stored in the session is an array, it is possible to use array dereference syntax in the element name to access a specific array key. This syntax works with set(), get(), delete(), add() and remove().
</p> 
 
<div class="syntax"><pre>
<span class="nx">Session</span><span class="o">::</span><span class="na">set</span><span class="p">(</span> 
    <span class="s1">&#39;user&#39;</span><span class="p">,</span> 
    <span class="k">array</span><span class="p">(</span> 
        <span class="s1">&#39;first_name&#39;</span> <span class="o">=&gt;</span> <span class="s1">&#39;John&#39;</span><span class="p">,</span> 
        <span class="s1">&#39;last_name&#39;</span>  <span class="o">=&gt;</span> <span class="s1">&#39;Smith&#39;</span> 
    <span class="p">)</span> 
<span class="p">);</span> 
<span class="c1">// This will echo John  </span> 
<span class="k">echo</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;user[first_name]&#39;</span><span class="p">);</span> 
</pre></div>
 
<p>Array dereferencing can be any number of layers deep.</p> 
 
<div class="syntax"><pre>
<span class="k">echo</span> <span class="nx">Session</span><span class="o">::</span><span class="na">get</span><span class="p">(</span><span class="s1">&#39;user[groups][0][name]&#39;</span><span class="p">);</span> 
</pre></div> 
<!-- PRINT: stop -->

<?php echo isset($pager) ? $pager : ''; ?>