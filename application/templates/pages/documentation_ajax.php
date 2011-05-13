<script language="JavaScript">
// Ajax
// http://www.itnewb.com/v/Beginners-Guide-to-AJAX-Asynchronous-JavaScript-and-XML/
function sendRequest(mode, url, args, async, callback, cArgs, respType, timeout) {

	// create object
	var httpRequest = false;
	if ( window.XMLHttpRequest ) { // mozilla, safari, opera, chrome, ie7
		httpRequest = new XMLHttpRequest();
	} else if ( window.ActiveXObject ) { // ie, avant, aol explorer
		try { httpRequest = new ActiveXObject("Msxml2.XMLHTTP") } // ie6
		catch (e) {
			try { httpRequest = new ActiveXObject("Microsoft.XMLHTTP") } // ie5
			catch(e) {
				httpRequest = false;
			}
		}
	}

	// object creation fail?
	if ( ! httpRequest ) {
		alert("Error: unable to create httpRequest object");
		return false;
	}

	// if GET with arguments append query string to url
	if ( mode == 'GET' && args.length ) url += '/' + args;

	// establish connection
	httpRequest.open(mode, url, async);

	// boolean timedOut; start out true
	// the response handler sets to false on success
	var timedOut = true;

	// create a timer to check the value of timedOut in (timeout) seconds;
	// if timedOut is true, abort the request and clean up
	setTimeout( function() {

		// timedOut true?
		if (timedOut) {
			httpRequest.abort(); // abort request
			alert("Error: ajax request timed out");
		}

		// nullify httpRequest
		httpRequest = null;

	}, timeout * 1000 );

	// if POST, set necessary request headers
	if ( mode == 'POST' ) {
		httpRequest.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		httpRequest.setRequestHeader('Content-Length', args.length);
	}

	// assign callback method via anonymous function
	httpRequest.onreadystatechange = function() {
		try {
			// response ready?
			if ( httpRequest.readyState == 4 ) {
				// request successful?
				if ( httpRequest.status == 200 ) {

					// set timedOut to false
					timedOut = false;

					// set response
					var response = respType == 'text' ?
						httpRequest.responseText :
						httpRequest.responseXML;

					// pass to callback method
					cArgs != null ?
						callback(response, cArgs) :
						callback(response);
				}
			}
		} catch(e) { alert("Error: communication exception"); }
	};

	// send request
	httpRequest.send(args);
}

//play game
function playGame(user_choice) {
	//var args = 'user_choice=' + user_choice; // POST
	var args = user_choice; // GET

	sendRequest('GET', '<?php echo APP_URI_BASE; ?>ajax/index', args, true, handlePlayGame, null, 'text', 30);
}

function handlePlayGame(response) {
	document.getElementById('ajax_response').innerHTML = '<div>' + response + '</div>';
}

</script>


<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Ajax Interaction</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Ajax Interaction
</div>
<!-- end breadcrumb -->

<p>
Ajax makes web pages feel more responsive by exchanging small amounts of data with the server behind the scenes, so that the entire web page does not have to be reloaded each time the user makes a change. This is meant to increase the web page's interactivity, speed, and usability.
</p>

<p>
An Ajax interaction is made up of three parts: a caller (a link, a button, a form, a clock, or any control that the user manipulates to launch the action), a server action, and a zone in the page to display the response of the action. You can build more complex interactions if the remote action returns data to be processed by a javascript function on the client side.
</p>

<p>
To give the developers the full control of their applications, InfoPotato doesn't come with any JavaScript libraries to help you enrich your PHP applications with Ajax.
</p>

<div class="notebox">
Only GET and POST requests are supported by InfoPotato.
</div>



<a href="#" name="user_rock" id="user_rock" onclick="playGame('making an ajax request');">Click Here</a>


<!-- begin ajax_response --> 
<div id="ajax_response"> 
 
</div> 
<!-- end ajax_response -->


<h2>Ajax GET or POST &mdash; Which to Use?</h2>

<p>
According to <a href="http://developer.yahoo.com/performance/rules.html#ajax_get" class="external_link">Yahoo's ySlow performance rule #15 - Use GET for Ajax requests</a>, the Yahoo! Mail team found that when using XMLHttpRequest, POST is implemented in the browsers as a two-step process: sending the headers first, then sending data. So it's best to use GET, which only takes one TCP packet to send (unless you have a lot of cookies). The maximum URL length in IE is 2K, so if you send more than 2K data you might not be able to use GET.
</p>

<p>
An interesting side affect is that POST without actually posting any data behaves like GET. Based on the HTTP specs, GET is meant for retrieving information, so it makes sense (semantically) to use GET when you're only requesting data, as opposed to sending data to be stored server-side.
</p>

<div class="notebox">
GET requests should be used for simply retrieving data from the server. POST requests are optimal when sending form data, or large sets of data, to the server.
</div>

<h2>Ajax Request URI</h2>

<p>
Due to the GET request URI pattern designed in InfoPotato, query string that contains data to be passed to web applications is not supported. When formatting the Ajax GET URI, you should follow this URI format:
</p>

<div class="syntax">
http://www.example.com/index.php/<span class="red">manager</span>/<span class="blue">method</span>/<span class="green">param1</span>/<span class="green">param2/<span class="green">param3/</span>
</div>

<p>
Just follow the regular URI parameters format when making an Ajax POST request. Since all the data sent to the server are stored in the HTTP request body instead of appearing in the URI.
</p>

</div> 
<!-- end onecolumn -->
