<?php
/**
 * Dispatcher
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

/**
 * Autoloading other core components
 *
 * @param   string $class_name the class name we are trying to load
 * @return  void
 */   
function __autoload($class_name) {
	$file = SYS_DIR.'core'.DS.strtolower($class_name).'.php';
	if ( ! file_exists($file)) {
		// In case one manager extends another manager
		$file = APP_MANAGER_DIR.strtolower($class_name).'.php';
	}
	require_once $file;
} 


/**
 * Dispatcher class
 */
class Dispatcher {
	/**
	 * Constructor
	 * 
	 * Sets instance
	 */   
	public function __construct() {
		// Set instance
		self::instance($this);
	}

	/**
	 * Parse incoming request to get the desiered manager, request method, and params
	 * Then the desginated manager prepares the related resources and sends response back to client
	 */ 
	public static function run() {	
		// Get the incoming HTTP request method (only support 'GET' and 'POST')
		// Other methods like 'PUT' or 'DELETE' will be treated as 'GET'
		$request_method = (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get';
		
		// Get URI string based on PATH_INFO, if server doesn't support PATH_INFO, only the default manager runs
		// The URI string has already been decoded, like urldecode()
		// It may contain UTF-8 characters beyond ASCII
		$request_uri = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

		// Get the target manager and its parameters
		$uri_segments = ! empty($request_uri) ? explode('/', $request_uri) : NULL;

		// Filter URI characters
		if (isset($uri_segments) && is_array($uri_segments)) {
			foreach ($uri_segments as $val) {
				$val = self::_filter_uri($val);
			}
		}
		// Get manager and manager method, use default if none given (case-insensitive)
		// All manager and manager method names are lowercased and no UTF8 encoded characters allowed
		$manager_name = ! empty($uri_segments[0]) ? strtolower($uri_segments[0]) : strtolower(DEFAULT_MANAGER);
		$method_name = ! empty($uri_segments[1]) ? strtolower($uri_segments[1]) : strtolower(DEFAULT_MANAGER_METHOD);
		
		// The read method is prefixed with the HTTP request method (get or post)
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
			Global_Functions::show_sys_error('An Error Was Encountered', 'Manager file does not exist', 'sys_error');
		}
		require_once $manager_file;

		// The name of user-defined manager class (case-insensitive)
		$manager_class = $manager_name.'_manager';
		// Function class_exists() is matched in a case-insensitive manner
		if ( ! class_exists($manager_class)) {
			Global_Functions::show_sys_error('An Error Was Encountered', 'Manager class does not exist', 'sys_error');				
		}

		// Instantiate the manager object
		$manager_obj = new $manager_class;
		
		// Checks if the manager method exists
		if ( ! method_exists($manager_obj, $real_method)) {
			Global_Functions::show_sys_error('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error');				
		}

		// The desginated manager prepares the related resources and sends response back to client
		$manager_obj->{$real_method}($params);
	}
	
	/**
	 * To get/set the object instance
	 * Making this function static makes it accessible without needing an instantiation of the class. 
	 * It acts at the class level rather than at the instance level
	 *
	 * @param   object $new_instance reference to new object instance
	 * @return  object $instance reference to object instance
	 */    
	public static function instance($new_instance = NULL) {
		static $instance = NULL;
		if (isset($new_instance) && is_object($new_instance)) {
			$instance = $new_instance;
		}
		return $instance;
	}

	/**
	 * Filter uri_segments for malicious characters
	 *
	 * This lets you specify with a regular expression which characters are permitted
	 * within your URLs. When someone tries to submit a URL with disallowed
	 * characters they will get a warning message.
	 *
	 * As a security measure you are STRONGLY encouraged to restrict URLs to
	 * as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
	 *
	 * @param	string
	 * @return	string
	 */
	private static function _filter_uri($str) {
		if ($str !== '') {
			// preg_quote() in PHP 5.3 escapes -, so the str_replace() and addition of - to preg_quote() is to maintain backwards
			// compatibility as many are unaware of how characters in the permitted_uri_chars will be parsed as a regex pattern
			if ( ! preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote(PERMITTED_URI_CHARS, '-'))."]+$|i", $str)) {
				Global_Functions::show_sys_error('An Error Was Encountered', 'The URI you submitted contains disallowed characters.', 'sys_error');
			}
		}

		// Convert programatic characters to entities
		$bad = array('$', '(', ')', '%28', '%29');
		$good = array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;');

		return str_replace($bad, $good, $str);
	}

}

// End of file: ./system/dispatcher.php