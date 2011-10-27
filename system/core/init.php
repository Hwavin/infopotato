<?php
/**
 * Set the PHP error reporting level. If you set this in php.ini, you remove this.
 *
 * When developing your application, it is highly recommended to enable notices
 * and strict warnings. Enable them by using: E_ALL | E_STRICT
 *
 * In production environments, it is typically desirable to disable PHP's error reporting
 * by setting the internal error_reporting flag to a value of 0.
 */
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL | E_STRICT);
        break;

    case 'production':
        error_reporting(0);
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
        'dispatcher', 
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
                file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.$class_name.'.php'));
            }
        } else {
            $file = SYS_CORE_DIR.$class_name.'.php';
        }
    } else {
        $file = APP_MANAGER_DIR.$class_name.'.php';
		// Checks if app manager file exists, for debug
	    if ( ! file_exists($file)) { 
		    halt('An Error Was Encountered', 'Manager file does not exist', 'sys_error', 404);
	    }
    }

	// Using require_once() in the __autoload() function is redundant.  
	// __autoload() is only called when php can't find your class definition.  
	// If your file containg your class was already included, the class defenition would already be loaded 
	// and __autoload() would not be called.  So save a little overhead and only use require() within __autoload()
	require $file;
} 

spl_autoload_register('auto_load');

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
function halt($heading, $message, $template = 'sys_error', $status_code = 404) {
	if (isset($status_code)) {
	    // HTTP status codes and messages
	    $stati = array(
		    400 => 'Bad Request',
		    401 => 'Authorization Required',
		    403 => 'Forbidden',
			404 => 'Not Found',
		    500 => 'Internal Server Error',
	    );
		
		if (isset($stati[$status_code])) {
		    $status_text = $stati[$status_code];
	    }

	    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	    if (substr(php_sapi_name(), 0, 3) == 'cgi') {
		    header("Status: {$status_code} {$status_text}", TRUE);
	    } elseif ($server_protocol == 'HTTP/1.1' || $server_protocol == 'HTTP/1.0') {
		    header($server_protocol." {$status_code} {$status_text}", TRUE, $status_code);
	    } else {
		    header("HTTP/1.1 {$status_code} {$status_text}", TRUE, $status_code);
	    }
	}
	
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
 *    __('Welcome back, :user', array(':user' => $username));
 *
 * [!!] The target language is defined by [I18n::$lang].
 * 
 * @uses    I18n::get
 * @param   string  text to translate
 * @param   array   values to replace in the translated text
 * @return  string
 */
function __($string, array $values = array()) {
    // Get the translation for this message
    $string = I18n::get($string);
    return empty($values) ? $string : strtr($string, $values);
}

/**
 * Recursively sanitizes an input variable:
 *
 * - Strips slashes if magic quotes are enabled
 * - Normalizes all newlines to LF
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
		// When Magic Quotes are on (it's on by default), 
		// all ' (single-quote), " (double quote), \ (backslash) and NULL characters 
		// are escaped with a backslash automatically.
		if (get_magic_quotes_gpc()) {
			// Remove backslashes added by magic quotes and return the user's raw input
			$value = stripslashes($value);
		} 

		if (strpos($value, "\r") !== FALSE) {
			// Standardize newlines
			$value = str_replace(array("\r\n", "\r"), "\n", $value);
		}
	}

	return $value;
}

// $_GET data is disallowed since InfoPotato utilizes URI segments 
// rather than traditional URI query strings
unset($_GET);

// One key aspect of Web application security is referring to variables with precision
// one should not use $_REQUEST as it is less exact, and therefore less secure, 
// than explicitly referring to $_GET, $_POST and $_COOKIE
unset($_REQUEST);

// The POST data can only be accessed in manager using $this->_POST_DATA
// $_COOKIE can be used by InfoPotato's Cookie class or your own Cookie process
// Remove backslashes added by magic quotes and return the user's raw input
// Normalizes all newlines to LF
// NOTE: $_SERVER and $_SESSION are not affected by magic_quotes
// $_POST, $_COOKIE, $_FILES and $_ENV were affected
$_POST = isset($_POST) ? sanitize($_POST) : array();
$_COOKIE = isset($_COOKIE) ? sanitize($_COOKIE) : array();
	
// End of file: ./system/core/init.php
