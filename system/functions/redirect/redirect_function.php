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
 * this could be used for example to create 301 redirects for search engine purposes
 * The default Response Code is 302 Found - a common way of performing a redirection
 *
 * @param string $url the url to be redirected, must start with http://
 * @param integer $http_response_code send a specific HTTP Response Code 
 */
function redirect_function($uri = '', $http_response_code = 302) {
	header("Location: ".$uri, TRUE, $http_response_code);
	exit;
}

/* End of file: ./system/functions/redirect/redirect_function.php */