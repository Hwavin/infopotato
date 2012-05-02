<?php
// Register given function as __autoload() implementation
spl_autoload_register('auto_load');

// $_GET data is disallowed 
// InfoPotato utilizes URI segments rather than traditional URI query strings
unset($_GET);

// One key aspect of Web application security is referring to variables with precision
// one should not use $_REQUEST as it is less exact, and therefore less secure, 
// than explicitly referring to $_GET, $_POST and $_COOKIE
unset($_REQUEST);

// $_COOKIE can be used directly by InfoPotato's Cookie class or your own Cookie process 
// Remove backslashes added by magic quotes and return the user's raw input
// Normalizes all newlines to LF
// NOTE: $_SERVER and $_SESSION are not affected by magic_quotes
// $_POST, $_COOKIE, $_FILES and $_ENV were affected
$_COOKIE = isset($_COOKIE) ? sanitize($_COOKIE) : array();

/**
 * Sets the error_reporting directive at runtime
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In production environments, it is typically desirable to disable PHP's error reporting
 * by setting the internal error_reporting flag to a value of 0.
 *
 * As E_STRICT is not included within E_ALL you have to explicitly enable this kind of error level.
 * Most of E_STRICT errors are evaluated at the compile time thus such errors are not reported
 * in the file where error_reporting is enhanced to include E_STRICT errors (and vice versa).
 */
switch (ENVIRONMENT) {
    case 'development':
        // Show all errors, warnings and notices including coding standards
		// Note: E_STRICT became part of E_ALL in PHP 5.4.0
		// Same as error_reporting(E_ALL | E_STRICT);
        ini_set('error_reporting', E_ALL | E_STRICT);
        break;

    case 'production':
	    // Same as error_reporting(0);
		ini_set('error_reporting', 0);
        break;

    default:
        exit('The application environment is not set correctly.');
}

/**
 * SPL Autoloading required core components
 *
 * @param   string $class_name the class name we are trying to load
 * @return  void
 */   
function auto_load($class_name) {
    $class_name = strtolower($class_name);
	
    // Create and use runtime files to speed up the parsing process for all the following requests
    // Dispatcher and Manager class are required for all app request
    $runtime_list = array(
        'manager', 
        'dumper', 
        'utf8',
        'i18n',
        'cookie',
        'session',
		'data', 
        'base_dao',
        'mysql_dao', 
        'mysqli_dao', 
        'postgresql_dao', 
        'sqlite_dao'
    );

    if (in_array($class_name, $runtime_list)) {
        if (SYS_RUNTIME_CACHE === TRUE) {
            // The runtime folder SYS_RUNTIME_DIR must be writable
			$file = SYS_RUNTIME_DIR.'~'.$class_name.'.php';
            if ( ! file_exists($file)) {
                // Return source with stripped comments and whitespace
				file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.$class_name.'.php'));
            }
        } else {
            $file = SYS_CORE_DIR.$class_name.'.php';
        }
    } else {
        $file = APP_MANAGER_DIR.$class_name.'.php';
		// Checks if app manager file exists, for debug
	    if ( ! file_exists($file)) { 
		    halt('An Error Was Encountered', 'Manager file does not exist', 'sys_error');
	    }
    }

	// Using require_once() in the __autoload() function is redundant.  
	// __autoload() is only called when php can't find your class definition.  
	// If your file containg your class was already included, the class defenition would already be loaded 
	// and __autoload() would not be called.  So save a little overhead and only use require() within __autoload()
	require $file;
} 

/**
 * Display system error
 *
 * This function takes an error message as input
 * and displays it using the specified template.
 * 
 * @param	string	the heading
 * @param	string	the message
 * @param	string	the template name
 * @param	int	the HTTP response status code
 * @return	string
 */
function halt($heading, $message, $template = 'sys_error') {
	if (ENVIRONMENT === 'development') {
        ob_start();
        require SYS_CORE_DIR.'sys_templates'.DS.$template.'.php';
        $buffer = ob_get_contents();
        ob_end_clean();
        echo $buffer;
        exit;
    }
}

/**
 * Dump variable
 *
 * Displays information about a variable in a human readable way
 * 
 * @param	mixed the variable to be dumped
 * @return	void
 */
function dump($var, $force_type = '', $collapsed = FALSE) {
    Dumper::dump($var, $force_type, $collapsed);
}

/**
 * Translation/internationalization function. The PHP function
 * [strtr](http://php.net/strtr) is used for replacing parameters.
 *
 * __('Welcome back, :user', array(':user' => $username));
 *
 * The target language is defined by [I18n::$lang].
 * 
 * @uses    I18n::get
 * @param   string  text to translate
 * @param   array   values to replace in the translated text
 * @return  string
 */
function __($str, array $values = array()) {
    // Get the translation for this message
    $str = I18n::get($str);
    return empty($values) ? $str : strtr($str, $values);
}

/**
 * Recursively sanitizes an input variable:
 *
 * Strips slashes if magic quotes are enabled
 * Normalizes all newlines to LF
 *
 * @param   mixed  any variable
 * @return  mixed  sanitized variable
 */
function sanitize($value) {
    if (is_array($value)) {
        foreach ($value as $key => $val) {
            // Recursively clean each value
            $value[$key] = sanitize($val);
		}
	} 
	
	if (is_string($value)) {	
		// NOTE: Magic Quotes has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0. 
		// And it will probably not exist in future versions at all.
		if (version_compare(PHP_VERSION, '5.4.0') == -1) {
			// When Magic Quotes are on (it's on by default), 
			// all ' (single-quote), " (double quote), \ (backslash) and NULL characters 
			// are escaped with a backslash automatically. This is identical to what addslashes() does.
			if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
				// Remove backslashes added by magic quotes and return the user's raw input
				$value = stripslashes($value);
			}
		}
		
		if (strpos($value, "\r") !== FALSE) {
			// Standardize newlines
			$value = str_replace(array("\r\n", "\r"), "\n", $value);
		}
	}

	return $value;
}


/**
 * Parse incoming request to get the desiered manager, request method, and params
 * Then the desginated manager prepares the related resources and sends response back to client
 */ 
function dispatch() {	
	// Get the incoming HTTP request method (only support 'GET' and 'POST')
	// Other methods like 'PUT' or 'DELETE' will be treated as 'GET'
	$request_method = (isset($_SERVER['REQUEST_METHOD']) &&  $_SERVER['REQUEST_METHOD'] === 'POST') ? 'post' : 'get';
	
	// Get URI string based on PATH_INFO, if server doesn't support PATH_INFO, only the default manager runs
	// The URI string in $_SERVER['PATH_INFO'] has already been decoded, like urldecode()
	// It may contain UTF-8 characters beyond ASCII
	$request_uri = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

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
	$manager_obj->_POST_DATA = isset($_POST) ? sanitize($_POST) : array();
	// Disable direct access to $_POST
	unset($_POST);

	// The uploaded files data can only be accessed in manager using $this->_FILES_DATA
    // $FILES will only be set when the form is enctype="multipart/form-data" and action="post"
	$manager_obj->_FILES_DATA = isset($_FILES) ? sanitize($_FILES) : array();
	// Disable direct access to $_FILES
	unset($_FILES);
	
	// Checks if the manager method exists
	if ( ! method_exists($manager_obj, $real_method)) {
		halt('An Error Was Encountered', "The requested manager method '{$real_method}' does not exist in object '{$manager_class}'", 'sys_error');				
	}

	// The desginated manager prepares the related resources and sends response back to client
	$manager_obj->{$real_method}($params);
}

// End of file: ./system/core/init.php