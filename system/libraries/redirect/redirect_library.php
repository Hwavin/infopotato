<?php
/**
 * URI Redirection
 * 
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\libraries\redirect;

class Redirect_Library {
    /**
     * URI Redirection
     * 
     * @param string $uri the uri to be redirected, must start with http://
     * @return void
     */
    public function redirect($uri) {
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
        exit();
    }
}

/* End of file: ./system/libraries/redirect/redirect_library.php */