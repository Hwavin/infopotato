<?php
/**
 * These global functions can be used directly in request dispatcher, worker, template, and data object
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
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
function show_sys_error($heading, $message, $template = 'sys_error') {
	ob_start();
	include(SYS_DIR.'core'.DS.'sys_templates'.DS.$template.'.php');
	$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
	exit;
}

/**
 * Dump variable, to be used before $this->response()
 *
 * Displays information about a variable in a human readable way
 * 
 * @param	mixed the variable to be dumped
 * @return	void
 */
function dump($var) {
	ob_start();
	include(SYS_DIR.'core'.DS.'sys_templates'.DS.'dump.php');
	$buffer = ob_get_contents();
	ob_end_clean();
	echo $buffer;
	exit;
}

/**
 * Script Functions Loader
 *
 * If script is located in a sub-folder, include the relative path from scripts folder.
 * Loaded script can be used by Model, View, and Controller
 *
 * @param   string $script the script plugin name
 * @return  void
 */    
function load_script($script) {
	$script = strtolower($script);
	
	// Is the script in a sub-folder? If so, parse out the filename and path.
	if (strpos($script, '/') === FALSE) {
		$path = '';
	} else {
		$x = explode('/', $script);
		$script = end($x);			
		unset($x[count($x)-1]);
		$path = implode(DS, $x).DS;
	}
	
	if ( ! preg_match('!^[a-z][a-z_]+$!', $script)) {
		if (ENVIRONMENT === 'development') {
			show_sys_error('A System Error Was Encountered', "Invalid script name '{$script}'", 'error_general');
		}
	}
	// Currently, all script functions are placed in system/scripts folder
	$file_path = SYS_DIR.'scripts'.DS.$path.$script.'.php';
	
	if ( ! file_exists($file_path)) {
		if (ENVIRONMENT === 'development') {
			show_sys_error('An Error Was Encountered', "Unknown script file '{$script}'", 'error_general');		
		}
	}
	return require_once($file_path);
}


// End of file: ./system/core/global_functions.php
