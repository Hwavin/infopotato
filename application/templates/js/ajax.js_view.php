
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
	if ( mode == 'GET' && args.length ) url += '?' + args;

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


//new post
function newEntry() {
	var entry_title = encodeURIComponent(document.getElementById('entry_title').value);
	var entry_url = encodeURIComponent(document.getElementById('entry_url').value);
	var entry_notes = encodeURIComponent(document.getElementById('entry_notes').value);
	var entry_tags = encodeURIComponent(document.getElementById('entry_tags').value);
	var entry_password = encodeURIComponent(document.getElementById('entry_password').value);
	var args = 'token=submitted' + '&entry_title=' + entry_title + '&entry_url=' + entry_url + '&entry_notes=' + entry_notes + '&entry_tags=' + entry_tags + '&entry_password=' + entry_password;

	sendRequest('POST', 'new-entry-process.php', args, true, handleNewEntry, null, 'text', 30);
}
function handleNewEntry(response) {
	document.getElementById('ajaxResponse').innerHTML = '<div class="errorbox">' + response + '</div>';
}

//contact me
function newEmail() {
	var contact_message = encodeURIComponent(document.getElementById('contact_message').value);
	var contact_subject = encodeURIComponent(document.getElementById('contact_subject').value);
	var contact_name = encodeURIComponent(document.getElementById('contact_name').value);
	var contact_email = encodeURIComponent(document.getElementById('contact_email').value);
	var args = 'token=submitted' + '&contact_message=' + contact_message + '&contact_subject=' + contact_subject + '&contact_name=' + contact_name + '&contact_email=' + contact_email;

	sendRequest('POST', 'contact-process.php', args, true, handleNewEmail, null, 'text', 30);
}
function handleNewEmail(response) {
	document.getElementById('ajaxResponse').innerHTML = '<div class="errorbox">' + response + '</div>';
}