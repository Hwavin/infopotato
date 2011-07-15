<?php
/**
 * Dispatcher
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
class Dispatcher {
	/**
	 * Forces use as a static class
	 * 
	 * @return Dispatcher
	 */
	private function __construct() { }
	
	/**
	 * Parse incoming request to get the desiered manager, request method, and params
	 * Then the desginated manager prepares the related resources and sends response back to client
	 */ 
	public static function dispatch() {	
		// Get the incoming HTTP request method (only support 'GET' and 'POST')
		// Other methods like 'PUT' or 'DELETE' will be treated as 'GET'
		$request_method = (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get';
		
		// Get URI string based on PATH_INFO, if server doesn't support PATH_INFO, only the default manager runs
		// The URI string has already been decoded, like urldecode()
		// It may contain UTF-8 characters beyond ASCII
		$request_uri = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

		// Get the target manager/method/parameters
		$uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : NULL;

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

		// Manager file
		$manager_file = APP_MANAGER_DIR.$manager_name.'_manager.php';

		// Checks if manager file exists 
		if ( ! file_exists($manager_file)) { 
			halt('An Error Was Encountered', 'Manager file does not exist', 'sys_error');
		}
		require_once $manager_file;

		// The name of user-defined manager class (case-insensitive)
		$manager_class = $manager_name.'_manager';
		// Function class_exists() is matched in a case-insensitive manner
		if ( ! class_exists($manager_class)) {
			halt('An Error Was Encountered', 'Manager class does not exist', 'sys_error');				
		}

		// Instantiate the manager object
		$manager_obj = new $manager_class;
		
		// Checks if the manager method exists
		if ( ! method_exists($manager_obj, $real_method)) {
			halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error');				
		}
		
		// Make the requested manager method and it's parameters available to that manager object
		$manager_obj->target_method = $real_method;
		$manager_obj->target_method_params = $params;

		// The desginated manager prepares the related resources and sends response back to client
		$manager_obj->{$real_method}($params);
	}

}

// End of file: ./system/core/dispatcher.php
