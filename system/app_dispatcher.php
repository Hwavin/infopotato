<?php
/**
 * Application Dispatcher Class
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

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
		'mysql_adapter', 
		'mysqli_adapter', 
		'postgresql_adapter', 
		'sqlite_adapter'
	);

	if (in_array($class_name, $runtime_list)) {
		$file = SYS_DIR.'core'.DS.'runtime'.DS.'~'.$class_name.'.php';
		if ( ! file_exists($file)) {
			file_put_contents($file, php_strip_whitespace(SYS_DIR.'core'.DS.$class_name.'.php'));
		}
	} else {
		// In case one app manager extends another app manager
		$file = APP_MANAGER_DIR.$class_name.'.php';
	}
	require_once $file;
	return;
} 

spl_autoload_register('autoload_components');

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