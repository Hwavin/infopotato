<script language="JavaScript">
// Ajax
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
	var args = 'user_choice=' + user_choice;

	sendRequest('POST', '<?php echo APP_URI_BASE; ?>ajax/', args, true, handlePlayGame, null, 'text', 30);
}

function handlePlayGame(response) {
	document.getElementById('ajax_response').innerHTML = '<div>' + response + '</div>';
}

</script>


<!-- begin onecolumn -->
<div id="onecolumn" class="inner"> 
	
<!-- begin breadcrumb -->
<div id="breadcrumb">
<h1 class="first_heading">Ajax</h1>
<a href="<?php echo APP_URI_BASE; ?>home">Home</a> &gt; <a href="<?php echo APP_URI_BASE; ?>documentation/">Documentation</a> &gt; Ajax
</div>
<!-- end breadcrumb -->

<p>
Basically, the Ajax equation involves incorporating the following technologies:
</p>

<ul>
<li>HTML / XHTML and CSS for presentation</li>
<li>Client-server communication via an XMLHttpRequest object</li>
<li>Crawling the DOM and modify DHTML via a client-side scripting language like JavaScript</li>
</ul>

<p>
To give the developers the full control of their applications, InfoPotato doesn't come with any JavaScript libraries to help you enrich your PHP applications with Ajax.
</p>

<div class="notebox">
Only GET and POST requests are supported by InfoPotato when working with Ajax
</div>



<a href="#" name="user_rock" id="user_rock" onclick="playGame('making an ajax request');">Click Here</a>


<!-- begin ajax_response --> 
<div id="ajax_response"> 
 
</div> 
<!-- end ajax_response -->

</div> 
<!-- end onecolumn -->
