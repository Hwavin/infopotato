<?php
/**
 * Dispatching based on the default URI routing pattern
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */ 
class Dispatcher{
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
	 * @param   string $manager the optional manager name
	 * @param   string $method the optional method name
	 * @return  void
	 */
	public static function run($manager = NULL, $method = NULL) {	
		// Dispatches all application requests to the given manager/method
		// Otherwise parses the URI
		if (isset($manager) && is_string($manager) && ! empty($manager)) {
		    // All application requests will be dispatched to this manager
			$manager = strtolower($manager).'_manager';
			
			// Only handles HTTP GET
			if (isset($method) && is_string($method) && ! empty($method)) {
			    $method = 'get_'.strtolower($method);
			} else {
			    $method = 'get_'.strtolower(APP_DEFAULT_MANAGER_METHOD);
			}

			$manager_obj = new $manager;
			
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
			// No need to check if the class exists
			$manager_class = $manager_name.'_manager';
			$manager_obj = new $manager_class;

			// The POST data can only be accessed in manager using $this->_POST_DATA
			if (isset($_POST)) {
				$manager_obj->_POST_DATA = sanitize($_POST);
				// Disable direct access to $_POST
				unset($_POST);
			}

			// The uploaded files data can only be accessed in manager using $this->_FILES_DATA
			// $FILES will only be set when the form is enctype="multipart/form-data" and action="post"
			if (isset($_FILES)) {
				$manager_obj->_FILES_DATA = sanitize($_FILES);
				// Disable direct access to $_FILES
				unset($_FILES);
			}

			// Checks if the manager method exists
			if ( ! method_exists($manager_obj, $real_method)) {
				halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error');				
			}

			// The desginated manager prepares the related resources and sends response back to client
			$manager_obj->{$real_method}($params);
		}
	}	
}

// End of file: ./system/core/dispatcher.php