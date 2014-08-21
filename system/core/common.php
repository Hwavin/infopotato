<?php
/**
 * Common shared functions
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class Common {
    /**
     * Prevent direct object creation
     * 
     * @return Common
     */
    private function __construct() {}
    
    /**
     * Display system error
     *
     * This function takes an error message as input,
     * log it to the defined file path and displays it using the specified template.
     * 
     * @param string the heading
     * @param string the message
     * @param string the template name
     * @return string
     */
    public static function halt($heading, $message, $template = 'sys_error') {
        // Log to capture all errors since some errors can't be manually captured
		// APP_LOG_DIR is defined in the bootstrap script
        Logger::log_debug(APP_LOG_DIR, $message);
            
        if (ENVIRONMENT === 'development') {
            ob_start();
            debug_print_backtrace();
            $debug_backtrace = ob_get_contents();
            ob_end_clean();
            
            ob_start();
            require SYS_CORE_DIR.'sys_templates'.DS.$template.'.php';
            $output = ob_get_contents();
            ob_end_clean();
			
            header('HTTP/1.1 503 Service Unavailable');
        }
        
        if (ENVIRONMENT === 'production') {
            // Display app specific 404 error page if defined, 
            // otherwise use the system default template
            if (defined('APP_404_MANAGER') && defined('APP_404_MANAGER_METHOD')) {
				// Show the 404 message, 404 status code should be specified in the APP_404_MANAGER
				\InfoPotato\core\Dispatcher::run(APP_404_MANAGER, APP_404_MANAGER_METHOD);
            } else {
                $output = file_get_contents(SYS_CORE_DIR.'sys_templates'.DS.'404_error.php');
				// Send the HTTP Status-Line to avoid soft 404 before outputting the custom 404 content
				// No need to send this 404 status code under 'development' environment
				header('HTTP/1.1 404 Not Found');
            }
        }
		
		// Send out the 'Content-Type' header
        header('Content-Type: text/html; charset=utf-8');
        // Print message and exit
        exit($output);
    }

}

// End of file: ./system/core/common.php