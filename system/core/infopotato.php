<?php
/**
 * InfoPotato
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
// Load the global functions
require(SYS_DIR.'core'.DS.'global_functions.php');

/**
 * Autoloading core components
 *
 * @param   string $class_name the class name we are trying to load
 * @return  boolean  success or failure
 */   
function __autoload($class_name) {
	$file = SYS_DIR.'core'.DS.strtolower($class_name).'.php';
	if ( ! file_exists($file)) {
		// In case one app worker extends another app worker
		$file = APP_WORKER_DIR.strtolower($class_name).'.php';
	}
	if ( ! file_exists($file)) {
		return FALSE;
	}
	return require_once($file);
} 


/**
 * InfoPotato class
 */
class InfoPotato {
	/**
	 * @var  string  the client requested uri string, 
	 * may contain UTF-8 characters beyond ASCII
	 */
	public $uri_string = ''; 
	
	/**
	 * Constructor
	 *
	 * Making it public permits an explicit call of the constructor! (e.g., $b = new Request_Dispatcher)
	 * 
	 * Sets instance
	 */   
	public function __construct() {
		// Set instance
		self::instance($this);
	}

	/**
	 * This begins the dispatch process of the framework
	 * The requested URI is parsed and the respective page worker and request method called
	 */ 
	public function run() {	
		// Get the incoming HTTP request method (e.g., 'GET', 'POST', 'PUT', 'DELETE')
		// 'PUT', 'DELETE' are not supported by most web browsers, but supported by cURL or other web services clients
		$request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		
		// Get URI string based on PATH_INFO, if server doesn't support PATH_INFO, only the default controller/action runs
		// The URI string has already been decoded, like urldecode()
		// No need to use UTF8::trim()
		$this->uri_string = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

		// Get the target worker and its parameters
		$segments = ! empty($this->uri_string) ? explode('/', $this->uri_string) : NULL;

		// Filter URI chars
		if (isset($segments) && is_array($segments)) {
			foreach ($segments as $val) {
				$val = self::_filter_uri($val);
			}
		}
		// Get worker, use default if none given 
		$worker_name = ! empty($segments[0]) ? $segments[0] : DEFAULT_WORKER;

		// Get parameters and put them into an array
		$params_cnt = count($segments);
		$params = array();
		for ($i = 1; $i < $params_cnt; $i++) {
			$params[] = $segments[$i];
		}

		// Worker file
		$worker_file = APP_WORKER_DIR.$worker_name.'_worker.php';

		// Checks if worker file exists 
		if ( ! file_exists($worker_file)) { 
			if (ENVIRONMENT === 'development') {
				show_sys_error('An Error Was Encountered', 'Worker file does not exist', 'sys_error');
			}
		}
		require_once($worker_file);

		// The names of user-defined classes are case-insensitive
		$worker_class = $worker_name.'_worker';
		// Function class_exists() is matched in a case-insensitive manner
		if ( ! class_exists($worker_class)) {
			if (ENVIRONMENT === 'development') {
				show_sys_error('An Error Was Encountered', 'Worker class does not exist', 'sys_error');	
			}			
		}

		// Instantiate the worker object
		$worker_obj = new $worker_class;
		
		// Checks if the default process method exists
		if ( ! method_exists($worker_obj, $request_method)) {	
			if (ENVIRONMENT === 'development') {
				show_sys_error('An Error Was Encountered', "The method '{$request_method}' does not exist in class '{$worker_obj}'", 'sys_error');
			}
		}

		// Dispatche incoming request to the correct worker (prepare and response)
		$worker_obj->$request_method($params);
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
	 * Filter segments for malicious characters
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
				if (ENVIRONMENT === 'development') {
					show_sys_error('An Error Was Encountered', 'The URI you submitted contains disallowed characters.', 'sys_error');
				}
			}
		}

		// Convert programatic characters to entities
		$bad	= array('$', 		'(', 		')',	 	'%28', 		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}

}

// End of file: ./system/infopotato.php
