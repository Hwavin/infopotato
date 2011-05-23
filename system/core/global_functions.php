<?php
/**
 * These global functions can be used directly in InfoPotato, dispatcher, manager, template, and data access object
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
	public static function dump($var, $force_type = '', $collapsed = FALSE) {
		new Dump($var, $force_type, $collapsed);
	}
	
}


// End of file: ./system/core/global_functions.php
