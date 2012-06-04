<?php
/*
Redirection with delay
----------------------------------------------
sleep(10);//seconds to wait..
header("Location:http://www.domain.com");
*/

/**
 * Redirect
 * 
 * The optional $http_response_code allows you to send a specific HTTP Response Code
 *
 * @param string $url the url to be redirected, must start with http://
 * @param integer $http_response_code send a specific HTTP Response Code 
 */
function redirect_function($uri = '', $http_response_code = 301) {
	// The headers below ensure that the page is giving out headers that will not be cached
	
	// Date in the past
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 
	// Always modified
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
 
	// HTTP/1.1
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", FALSE);
 
	// HTTP/1.0
	header("Pragma: no-cache");

	// Explictly specify the 301 response status code
	header('HTTP/1.1 301 Moved Permanently');
	header("Location: ".$uri);
	exit;
}

/* End of file: ./system/functions/redirect/redirect_function.php */