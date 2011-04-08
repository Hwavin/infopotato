<?php
/**
 * These global functions can be used directly in InfoPotato, worker, template, and data object
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2011 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

class Global_Functions {
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
	public static function show_sys_error($heading, $message, $template = 'sys_error') {
		if (ENVIRONMENT === 'development') {
			ob_start();
			include(SYS_DIR.'core'.DS.'sys_templates'.DS.$template.'.php');
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
	public static function dump($var) {
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
	 *
	 * @param   string $script the script plugin name
	 * @return  void
	 */    
	public static function load_script($script) {
		$orig_script = strtolower($script);
		
		// Is the script in a sub-folder? If so, parse out the filename and path.
		if (strpos($script, '/')) {
			$script = str_replace('/', DS, pathinfo($orig_script, PATHINFO_DIRNAME)).DS.substr(strrchr($orig_script, '/'), 1);
		}

		// Currently, all script functions are placed in system/scripts folder
		$file_path = SYS_DIR.'scripts'.DS.$script.'.php';
		
		if ( ! file_exists($file_path)) {
			self::show_sys_error('An Error Was Encountered', "Unknown script file '{$orig_script}'", 'sys_error');		
		}
		return require_once($file_path);
	}
}


// End of file: ./system/core/global_functions.php
