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
	$string = I18n::get($string, DEFAULT_I18N);
	return empty($values) ? $string : strtr($string, $values);
}

/**
 * Autoloading required components
 *
 * @param   string $class_name the class name we are trying to load
 * @return  void
 */   
function autoload_components($class_name) {
	$class_name = strtolower($class_name);
	
	// Create and use runtime files to speed up the parsing process for all the following requests
	// Dispatcher and Manager class are required for each app request
	// The runtime folder must be writable
	$runtime_list = array(
		'dispatcher', 
		'manager', 
		'data', 
		'data_adapter', 
		'global_functions', 
		'dump', 
		'utf8',
		'i18n',
		'mysql_adapter', 
		'mysqli_adapter', 
		'postgresql_adapter', 
		'sqlite_adapter'
	);

	if (in_array($class_name, $runtime_list)) {
		if (SYS_RUNTIME_CACHE === TRUE) {
			// SYS_RUNTIME_DIR must be writable
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
spl_autoload_register('autoload_components');

/**
 * Application Dispatcher Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
/**
 * It encapsulates {@link Dispatcher} which provides the actual implementation.
 * By writing your own App_Dispatcher class, you can customize some functionalities of Dispatcher
 * and use them in the bootstrap script
 */
final class App_Dispatcher extends Dispatcher {
	public function test() {
		echo $this->uri_string;
	}
}

// End of file: ./system/app_dispatcher.php 