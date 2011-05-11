<?php
/*
-- Option 1
----------------------------------------------
sleep(10);//seconds to wait..
header("Location:http://www.domain.com");

-- Option 2
----------------------------------------------
//  refresh / redirect to an external web page
//  ------------------------------------------
header( 'refresh: 0; url=http://www.example.net' );
echo '<h1>You won\'t know what hit you!</h1>';
*/

/**
 * Redirect
 *
 * @param string $url the url to be redirected
 * @param integer $delay how many seconds to be delayed
 * @param boolean $js whether to return JavaScript code for redirection
 * @param boolean $js_wrapped whether to use <script> tag when returing JavaScript
 * @param boolean $return whether to return JavaScript code
 */
function redirect_function($url, $delay = 0, $js = FALSE, $js_wrapped = TRUE, $return = FALSE) {
    $delay = (int) $delay;
    if ( ! $js) {
        if (headers_sent() || $delay > 0) {
            echo <<<EOT
				<html>
				<head>
				<meta http-equiv="refresh" content="{$delay};URL={$url}" />
				</head>
				</html>
EOT;
            exit;
        } else {
            header("Location: {$url}");
            exit;
        }
    }

    $out = '';
    if ($js_wrapped) {
        $out .= '<script language="JavaScript" type="text/javascript">';
    }
	
    if ($delay > 0) {
        $out .= "window.setTimeout(function () { document.location='{$url}'; }, {$delay});";
    } else {
        $out .= "document.location='{$url}';";
    }
	
    if ($js_wrapped) {
        $out .= '</script>';
    }

    if ($return) {
        return $out;
    }

    echo $out;
    exit;
}

/* End of file: ./system/functions/redirect/redirect_function.php */