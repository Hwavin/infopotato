<script type="text/javascript" src="<?php echo APP_URI_BASE; ?>js/index/ajax_request.js"></script> 

<script type="text/javascript"> 
function get_example() {
	AjaxRequest.get({
		'url':'<?php echo APP_URI_BASE; ?>ajax/index/param1/param2',
		'onSuccess':function(req) { 
			document.getElementById('ajax_response').innerHTML = req.responseText; 
		}
	});
}

function post_example() {
	AjaxRequest.post({
		'url':'<?php echo APP_URI_BASE; ?>ajax/index',
		'parameters':{'a':'1', 'b':'2', 'c':'3'},
		'onSuccess':function(req) { 
						document.getElementById('ajax_response2').innerHTML = req.responseText; 
					}
	});
}
</script>

<div class="row">
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<!-- PRINT: start -->
<h1 class="first_heading">Ajax Interaction</h1>
<!-- PRINT: stop -->
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/core/">Core Topics</a> &gt; Ajax Interaction
</div>
<!-- end breadcrumb -->

<a href="<?php echo APP_URI_BASE; ?>print/index/<?php echo base64_encode('documentation/core/ajax/'); ?>" class="print">Print</a>

<!-- PRINT: start -->
<p>
Ajax makes web pages feel more responsive by exchanging small amounts of data with the server behind the scenes, so that the entire web page does not have to be reloaded each time the user makes a change. This is meant to increase the web page's interactivity, speed, and usability.
</p>

<p>
An Ajax interaction is made up of three parts: a caller (a link, a button, a form, a clock, or any control that the user manipulates to launch the action), a server action, and a zone in the page to display the response of the action. You can build more complex interactions if the remote action returns data to be processed by a javascript function on the client side.
</p>

<p>
To give the developers the full control of their applications, InfoPotato doesn't come with any JavaScript libraries to help you enrich your PHP applications with Ajax. So this means you can choose any Ajax libraries you prefer and just let InfoPotato manager to handle the Ajax request. Nothing new.
</p>

<h2>Understanding Ajax Performance</h2>

<p>
Instead of requesting a replacement page as a result of a user action, a packet of data is sent to the server (usually encoded as JSON text) and the server responds with another packet (also typically JSON-encoded) containing data. A JavaScript program uses that data to update the browser’s display. The amount of data transferred is significantly reduced, and the time between the user action and the visible feedback is also significantly reduced. The amount of work that the server must do is reduced. The amount of work that the browser must do is reduced. The amount of work that the Ajax programmer must do, unfortunately, is likely to increase. That is one of the trade-offs.
</p>

<div class="notebox">
Browsers tend to spend little time running JavaScript. Most of their time is spent in the DOM.
</div>

<h2>Ajax GET or POST &mdash; Which to Use?</h2>

<p>
According to <a href="http://developer.yahoo.com/performance/rules.html#ajax_get" class="external_link">Yahoo's ySlow performance rule #15 - Use GET for Ajax requests</a>, the Yahoo! Mail team found that when using XMLHttpRequest, POST is implemented in the browsers as a two-step process: sending the headers first, then sending data. So it's best to use GET, which only takes one TCP packet to send (unless you have a lot of cookies). The maximum URL length in IE is 2K, so if you send more than 2K data you might not be able to use GET.
</p>

<div class="notebox">
Only GET and POST requests are supported by InfoPotato.
</div>

<p>
An interesting side affect is that POST without actually posting any data behaves like GET. Based on the HTTP specs, GET is meant for retrieving information, so it makes sense (semantically) to use GET when you're only requesting data, as opposed to sending data to be stored server-side.
</p>

<div class="notebox">
GET requests should be used for simply retrieving data from the server. POST requests are optimal when sending form data, or large sets of data, to the server.
</div>

<h2>How to Format Ajax GET/POST Request URI</h2>

<p>
Due to the GET request URI pattern designed in InfoPotato, GET request with query string that contains data to be passed to web applications is not supported. When formatting the Ajax GET URI, you should follow this URI format:
</p>

<div class="syntax">
http://www.example.com/index.php/<span class="red">manager</span>/<span class="blue">method</span>/<span class="green">param1</span>/<span class="green">param2/<span class="green">param3/</span>
</div>

<p>
Just follow the regular URI parameters format when making an Ajax POST request. Since all the data sent to the server are stored in the HTTP request body instead of appearing in the URI.
</p>

<h2>Make A Simple Ajax GET Request</h2>

<p>
To demonstrate the ease of using Ajax in InfoPotato, we will use <a href="http://www.ajaxtoolbox.com/" class="external_link">AjaxRequest Library</a> to make a simple Ajax GET request to the ajax_manager. Of course you can choose the other Ajax libraries based on your own preference.
</p>

<div class="syntax"><pre><span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span> <span class="na">src=</span><span class="s">&quot;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s">js/index/ajax_request.js&quot;</span><span class="nt">&gt;&lt;/script&gt;</span> 
 
<span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span><span class="nt">&gt;</span> 
<span class="kd">function</span> <span class="nx">get_example</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nx">AjaxRequest</span><span class="p">.</span><span class="nx">get</span><span class="p">({</span> 
    <span class="s1">&#39;url&#39;</span><span class="o">:</span><span class="s1">&#39;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s1">ajax/index/param1/param2&#39;</span><span class="p">,</span> 
    <span class="s1">&#39;onSuccess&#39;</span><span class="o">:</span><span class="kd">function</span><span class="p">(</span><span class="nx">req</span><span class="p">)</span> <span class="p">{</span> 
	            <span class="nb">document</span><span class="p">.</span><span class="nx">getElementById</span><span class="p">(</span><span class="s1">&#39;ajax_response&#39;</span><span class="p">).</span><span class="nx">innerHTML</span> <span class="o">=</span> <span class="nx">req</span><span class="p">.</span><span class="nx">responseText</span><span class="p">;</span> 
                <span class="p">}</span> 
    <span class="p">});</span> 
<span class="p">}</span> 
<span class="nt">&lt;/script&gt;</span> 
 
<span class="nt">&lt;button</span> <span class="na">onClick=</span><span class="s">&quot;get_example();return false;&quot;</span><span class="nt">&gt;</span>Make an Ajax GET request<span class="nt">&lt;/button&gt;</span> 
 
<span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;ajax_response&quot;</span><span class="nt">&gt;&lt;/div&gt;</span> 
</pre></div>

<p>
When you click the button, an Ajax request is made and sent to the ajax_manager. In this example, the manager will print out "This is an Ajax GET request :)". And the the corresponding response DIV is updated without reloading the whole page.
</p>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">get_index</span><span class="p">(</span><span class="nv">$params</span> <span class="o">=</span> <span class="k">array</span><span class="p">())</span> <span class="p">{</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_disable_cache</span><span class="p">();</span> 
    <span class="nv">$param1</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$params</span><span class="p">[</span><span class="m">0</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
    <span class="nv">$param2</span> <span class="o">=</span> <span class="nb">isset</span><span class="p">(</span><span class="nv">$params</span><span class="p">[</span><span class="m">1</span><span class="p">])</span> <span class="o">?</span> <span class="nv">$params</span><span class="p">[</span><span class="m">1</span><span class="p">]</span> <span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">;</span> 
		
    <span class="k">echo</span> <span class="s1">&#39;This is an Ajax GET request with &#39;</span><span class="o">.</span><span class="nv">$param1</span><span class="o">.</span> <span class="s1">&#39; and &#39;</span><span class="o">.</span><span class="nv">$param2</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div>

<!-- PRINT: stop -->

<div class="greenbox">
<button onClick="get_example();return false;">Make an Ajax GET request</button> 
<div id="ajax_response"></div> 
</div> 

<!-- PRINT: start -->

<h2>Make An Ajax POST Request</h2>

<div class="syntax"><pre><span class="nt">&lt;script </span><span class="na">type=</span><span class="s">&quot;text/javascript&quot;</span><span class="nt">&gt;</span> 
<span class="kd">function</span> <span class="nx">post_example</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nx">AjaxRequest</span><span class="p">.</span><span class="nx">post</span><span class="p">({</span> 
        <span class="s1">&#39;url&#39;</span><span class="o">:</span><span class="s1">&#39;</span><span class="cp">&lt;?php</span> <span class="k">echo</span> <span class="nx">APP_URI_BASE</span><span class="p">;</span> <span class="cp">?&gt;</span><span class="s1">ajax/index&#39;</span><span class="p">,</span> 
	 <span class="s1">&#39;parameters&#39;</span><span class="o">:</span><span class="p">{</span><span class="s1">&#39;a&#39;</span><span class="o">:</span><span class="s1">&#39;1&#39;</span><span class="p">,</span> <span class="s1">&#39;b&#39;</span><span class="o">:</span><span class="s1">&#39;2&#39;</span><span class="p">,</span> <span class="s1">&#39;c&#39;</span><span class="o">:</span><span class="s1">&#39;3&#39;</span><span class="p">},</span> 
	 <span class="s1">&#39;onSuccess&#39;</span><span class="o">:</span><span class="kd">function</span><span class="p">(</span><span class="nx">req</span><span class="p">)</span> <span class="p">{</span> 
			 <span class="nb">document</span><span class="p">.</span><span class="nx">getElementById</span><span class="p">(</span><span class="s1">&#39;ajax_response2&#39;</span><span class="p">).</span><span class="nx">innerHTML</span> <span class="o">=</span> <span class="nx">req</span><span class="p">.</span><span class="nx">responseText</span><span class="p">;</span> 
		     <span class="p">}</span> 
    <span class="p">});</span> 
<span class="p">}</span> 
<span class="nt">&lt;/script&gt;</span> 
 
<span class="nt">&lt;button</span> <span class="na">onClick=</span><span class="s">&quot;post_example();return false;&quot;</span><span class="nt">&gt;</span>Make an Ajax POST request<span class="nt">&lt;/button&gt;</span> 
 
<span class="nt">&lt;div</span> <span class="na">id=</span><span class="s">&quot;ajax_response2&quot;</span><span class="nt">&gt;&lt;/div&gt;</span> 
</pre></div>

<div class="syntax"><pre>
<span class="k">public</span> <span class="k">function</span> <span class="nf">post_index</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nv">$this</span><span class="o">-&gt;</span><span class="na">_disable_cache</span><span class="p">();</span> 
    <span class="k">echo</span> <span class="s1">&#39;&lt;pre&gt;&#39;</span><span class="p">;</span> 
    <span class="nb">print_r</span><span class="p">(</span><span class="nv">$this</span><span class="o">-&gt;</span><span class="na">POST_DATA</span><span class="p">);</span> 
    <span class="k">echo</span> <span class="s1">&#39;&lt;/pre&gt;&#39;</span><span class="p">;</span> 
<span class="p">}</span> 
</pre></div>

<p>
When responding to Ajax requests, you often will want to disable browser caching by adding the following headers in your manager method.
</p>

<div class="syntax"><pre>
<span class="k">private</span> <span class="k">function</span> <span class="nf">_disable_cache</span><span class="p">()</span> <span class="p">{</span> 
    <span class="nb">header</span><span class="p">(</span><span class="s1">&#39;Pragma: no-cache&#39;</span><span class="p">);</span>        
    <span class="nb">header</span><span class="p">(</span><span class="s1">&#39;Cache-control: no-cache&#39;</span><span class="p">);</span>        
    <span class="nb">header</span><span class="p">(</span><span class="s2">&quot;Expires: Mon, 26 Jul 1997 05:00:00 GMT&quot;</span><span class="p">);</span> 
<span class="p">}</span> 
</pre></div>

<!-- PRINT: stop -->

<div class="greenbox">
<button onClick="post_example();return false;">Make an Ajax POST request</button>
<div id="ajax_response2"></div>
</div> 

<?php echo isset($pager) ? $pager : ''; ?>

</div> 
