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
    // Dispatcher and Manager class are required for each app request
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
        // In case one app manager extends another app manager
        $file = APP_MANAGER_DIR.$class_name.'.php';
    }
    require_once $file;
    return;
} 
// PHP 5 >= 5.1.2
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
 * @return	string
 */
function halt($heading, $message, $template = 'sys_error') {
    if (ENVIRONMENT === 'development') {
        ob_start();
        require_once SYS_CORE_DIR.'sys_templates'.DS.$template.'.php';
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
 * @param   string  source language
 * @return  string
 */
function __($string, array $values = NULL) {
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


/**
 * Reverts the effects of the `register_globals` PHP setting by unsetting
 * all global varibles except for the default super globals (GPCS, etc),
 * which is a [potential security hole.][ref-wikibooks]
 *
 * [ref-wikibooks]: http://en.wikibooks.org/wiki/PHP_Programming/Register_Globals
 *
 * @return  void
 */
function disable_register_globals() {
	if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
		// Prevent malicious GLOBALS overload attack
		echo "Global variable overload attack detected! Request aborted.\n";

		// Exit with an error status
		exit(1);
	}

	// Get the variable names of all globals
	$global_variables = array_keys($GLOBALS);

	// Remove the standard global variables from the list
	$global_variables = array_diff($global_variables, array(
		'_COOKIE',
		'_ENV',
		'_GET',
		'_FILES',
		'_POST',
		'_REQUEST',
		'_SERVER',
		'_SESSION',
		'GLOBALS',
	));

	foreach ($global_variables as $name) {
		// Unset the global variable, effectively disabling register_globals
		unset($GLOBALS[$name]);
	}
}

// This feature is a great security risk
// you should ensure that register_globals is Off for all scripts 
// (as of PHP 4.2.0 this is the default).
if (ini_get('register_globals')) {
	// Reverse the effects of register_globals
	disable_register_globals();
}
	
// End of file: ./system/core/init.php
