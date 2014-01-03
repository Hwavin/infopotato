<?php
/**
 * Dispatching based on the INFO_PATH URI routing pattern
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */ 

namespace InfoPotato\core;

class Dispatcher {
    /**
     * Prevent direct object creation
     * 
     * @return Dispatcher
     */
    private function __construct() {}
    
    /**
     * Parse incoming request to get the desiered manager, request method, and optional parameters
     * Then the desginated manager prepares the related resources and sends response back to client
     *
     * @param string $manager the optional manager name
     * @param string $method the optional method name
     * @return void
     */
    public static function run($manager = NULL, $method = NULL) {
        // Dispatches all application requests to the given manager/method
        // Otherwise parses the URI
        if (isset($manager) && is_string($manager) && ! empty($manager)) {
            // All application requests will be dispatched to this manager
            $manager_class = strtolower($manager).'_manager';
            
            // Only handles HTTP GET
            if (isset($method) && is_string($method) && ! empty($method)) {
                $method = 'get_'.strtolower($method);
            } else {
                $method = 'get_'.strtolower(APP_DEFAULT_MANAGER_METHOD);
            }

            // Autoload will include the target app manager file
            // Prefix namespace. Using <code>new 'app\managers\\'.$manager_class</code> won't work
            $manager_class = APP_MANAGER_NAMESPACE.'\\'.$manager_class;
            $manager_obj = new $manager_class;
            
            // The desginated manager prepares the related resources and sends response back to client
            $manager_obj->{$method}();
        } else {
            // Putting the floowing code in a class or function will make it become a shared function,
            // but the truth is those code should only be executed once per request
            
            // Get the incoming HTTP request method (only support 'GET' and 'POST')
            // Other methods like 'PUT' or 'DELETE' will be treated as 'GET'
            $request_method = (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get';
            
            // Get URI string based on PATH_INFO
            // The URI string in $_SERVER['PATH_INFO'] has already been decoded, like urldecode()
            // It may contain UTF-8 characters beyond ASCII
            if (isset($_SERVER['PATH_INFO'])) {
                $request_uri = trim($_SERVER['PATH_INFO'], '/');
            } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
                // Check for $_SERVER['ORIG_PATH_INFO'] when $_SERVER['PATH_INFO'] is absent
                // When we use Apache mod_rewrite to hide index.php, the $_SERVER['PATH_INFO'] will be missing 
                // but ORIG_PATH_INFO may be there with the information PATH_INFO was supposed to have
                // This is different on IIS, IIS keeps the trailing slash in both PATH_INFO and ORIG_PATH_INFO 
                // if presented in URI and set PATH_INFO to '/' when 'index.php/' being requested, 
                // so need to remove the script name
                $request_uri = trim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['ORIG_PATH_INFO']), '/');
            } else {
                // When server doesn't support PATH_INFO nor ORIG_PATH_INFO, only the default manager runs
                $request_uri = '';
            }
            
            // Get the target manager/method/parameters
            $uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : array();
            
            // Get manager and manager method, use default if none given (case-insensitive)
            // All manager and manager method names are lowercased and no UTF8 encoded characters allowed
            $manager_name = ! empty($uri_segments[0]) ? strtolower($uri_segments[0]) : strtolower(APP_DEFAULT_MANAGER);
            $method_name = ! empty($uri_segments[1]) ? strtolower($uri_segments[1]) : strtolower(APP_DEFAULT_MANAGER_METHOD);
            
            // The real method is prefixed with the HTTP request method (get or post)
            $real_method = $request_method.'_'.$method_name;
            
            // Get parameters and put them into an array
            // Parameters are case-sensitive
            $params_cnt = count($uri_segments);
            $params = array();
            for ($i = 2; $i < $params_cnt; $i++) {
                $params[] = $uri_segments[$i];
            }
            
            // The name of user-defined manager class (case-insensitive)
            // If the file containg the class was already included, the class defenition would already be loaded 
            $manager_class = $manager_name.'_manager';

            // Autoload will include the target app manager file
            // Prefix namespace. Using <code>new 'app\managers\\'.$manager_class</code> won't work
            $manager_class = APP_MANAGER_NAMESPACE.'\\'.$manager_class;
            $manager_obj = new $manager_class;
            
            // The POST data can only be accessed in manager using $this->_POST_DATA
            if (isset($_POST)) {
                $manager_obj->_POST_DATA = self::sanitize($_POST);
                // Disable direct access to $_POST
                unset($_POST);
            }
            
            // The uploaded files data can only be accessed in manager using $this->_FILES_DATA
            // $FILES will only be set when the form is enctype="multipart/form-data" and action="post"
            if (isset($_FILES)) {
                // Even though PHP doc says that magic_quotes_gpc affects HTTP Request data (GET, POST, and COOKIE)
                // $_FILES comes through POST request, so it's affected. 
                $manager_obj->_FILES_DATA = self::sanitize($_FILES);
                // Disable direct access to $_FILES
                unset($_FILES);
            }
            
            // $_COOKIE can be used directly by InfoPotato's Cookie class or your own Cookie process 
            // Remove backslashes added by magic quotes and return the user's raw input
            // Normalizes all newlines to LF
            // NOTE: $_SERVER and $_SESSION are not affected by magic_quotes
            // $_POST, $_COOKIE and $_FILES are affected
            $_COOKIE = isset($_COOKIE) ? self::sanitize($_COOKIE) : array();

            // Checks if the manager method exists
            if ( ! method_exists($manager_obj, $real_method)) {
                Common::halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in '{$manager_class}'", 'sys_error');                
            }
            
            // The desginated manager prepares the related resources and sends response back to client
            $manager_obj->{$real_method}($params);
        }
    }
    
    /**
     * Recursively sanitizes an input variable
     *
     * Strips slashes if magic quotes are enabled
     * Standardize newlines using PHP_EOL
     *
     * @param mixed any variable
     * @return mixed sanitized variable
     */
    private static function sanitize($str) {
        if (is_array($str)) {
            foreach ($str as $key => $val) {
                // Recursively clean each string
                $str[$key] = self::sanitize($val);
            }
        } 
        
        if (is_string($str)) {    
            // NOTE: Magic Quotes has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0. 
            // And it will probably not exist in future versions at all.
            if (version_compare(PHP_VERSION, '5.4.0', '<')) {
                // When Magic Quotes are on (it's on by default), 
                // all ' (single-quote), " (double quote), \ (backslash) and NULL characters 
                // are escaped with a backslash automatically. This is identical to what addslashes() does.
                // Even though PHP doc says that magic_quotes_gpc affects HTTP Request data (GET, POST, and COOKIE)
                // $_FILES comes through POST request, so it's affected. 
                if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
                    // Remove backslashes added by magic quotes and return the user's raw input
                    $str = stripslashes($str);
                }
            }
            
            // Standardize newlines to always represent newlines as the value of PHP_EOL 
            // instead of having e.g. \r\n in one place and \n in another.
            // Read http://php.net/manual/en/regexp.reference.subpatterns.php for the use of ?: in subpattern
            preg_replace('/(?:\r\n|[\r\n])/', PHP_EOL, $str);
        }
        
        return $str;
    }

}

// End of file: ./system/core/dispatcher.php