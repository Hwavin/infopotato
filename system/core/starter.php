<?php
/**
 * Initial environment settings and autoloading
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class Starter {
    /**
     * Prevent direct object creation
     * 
     * @return Starter
     */
    private function __construct() {}
    
    /**
     * Security setting and autoloading
     *
     * @return  void
     */
    public static function start() {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            exit('InfoPotato requires PHP 5.3.0 or newer. You are currently running PHP '. PHP_VERSION .'.');
        }

        // $_GET is disallowed since InfoPotato utilizes URI segments rather than traditional URI query strings
        // $_REQUEST as it is less exact, and therefore less secure
        // $_ENV is disabllowed since it's not as commonly used and you can still get access to the environment variables through getenv()
        // $GLOBALS contains all other superglobals (so no need to duplicate) and every variable with global scope (should be disabled for security)
        // $_SERVER, $_SESSION, and $_COOKIE are untouched
        unset($_GET, $_REQUEST, $_ENV, $GLOBALS);

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
                if (version_compare(PHP_VERSION, '5.4.0', '<')) {
                    // Same as error_reporting(E_ALL | E_STRICT);
                    ini_set('error_reporting', E_ALL | E_STRICT);
                } else  {
                    ini_set('error_reporting', E_ALL);
                }
                break;

            case 'production':
                // Turn off all error reporting
                // Same as error_reporting(0);
                ini_set('error_reporting', 0);
                break;

            default:
                exit('The application environment is not set correctly.');
        }


        // Register given anonymous function as __autoload() implementation
        // Anonymous functions become available since PHP 5.3.0
        // By uuing this anonymous function we can make sure other components can't access it
        spl_autoload_register(function ($class_name) {
            // Trim the leading/tailing backslash in case they are added
            $class_name = trim(strtolower($class_name), '\\');
            
            // Core components
            if (strpos($class_name, 'infopotato\core\\') !== FALSE) {
                // Remove the namespaces of core components before parsing
                // It is very important to realize that because the backslash is used as an escape character 
                // within strings, it should always be doubled when used inside a string.
                // Note that trailing backslash of core namespace is trimmed so the returned $calss_name doesn't have leading backslash
                $class_name = str_replace('infopotato\core\\', '', $class_name);

                // Create and use core files to speed up the parsing process for all the following requests.
                // All core conponents must be listed below except Starter (this file, loaded in entry point script)
                $core = array(
                    'dispatcher', 
                    'manager', 
                    'common',
                    'dumper', 
                    'logger', 
                    'validator', 
                    'php_utf8',
                    'i18n',
                    'cookie',
                    'session',
                    'data', 
                    'base_dao',
                    'mysql_dao', 
                    'mysqli_dao', 
                    'postgresql_dao', 
                    'sqlite_dao',
                );

                if (in_array($class_name, $core)) {
                    $source_file = SYS_CORE_DIR.$class_name.'.php';
                    
                    // Checks if core component file exists
                    if ( ! file_exists($source_file)) { 
                        Common::halt('An Error Was Encountered', "Core component file '{$class_name}.php' is missing!", 'sys_error');
                    }
                    
                    // Load stripped source when runtime cache is turned-on
                    // Otherwise load the origional unstripped file
                    $file = $source_file;

                    if (RUNTIME_CACHE === TRUE) {
                        // The runtime folder must be writable
                        $file = SYS_RUNTIME_CACHE_DIR.'~'.$class_name.'.php';
                        if ( ! file_exists($file)) {
                            // Return source with stripped comments and whitespace
                            file_put_contents($file, php_strip_whitespace($source_file));
                        }
                    }
                } else {
                    Common::halt('An Error Was Encountered', "Unknown core component file '{$class_name}.php' is called by mistake!", 'sys_error');
                }
            } 

            // APP Managers
            if (strpos($class_name, APP_MANAGER_NAMESPACE) !== FALSE) {
                // Remove the namespaces of app managers before parsing
                // It is very important to realize that because the backslash is used as an escape character 
                // within strings, it should always be doubled when used inside a string.
                // Also trim the leading backslash since APP_MANAGER_NAMESPACE may not be defined with trailing backslash
                $class_name = trim(str_replace(APP_MANAGER_NAMESPACE, '', $class_name));
                
                // Note: an app manager could be a subclass of another app manager
                $source_file = APP_MANAGER_DIR.$class_name.'.php';

                // Checks if app manager file exists
                if ( ! file_exists($source_file)) { 
                    Common::halt('An Error Was Encountered', "App Manager file '{$class_name}.php' does not exist!", 'sys_error');
                }
                
                // Load stripped source when runtime cache is turned-on
                // Otherwise load the origional unstripped file
                $file = $source_file;

                if (RUNTIME_CACHE === TRUE) {
                    // The runtime folder must be writable
                    $file = APP_RUNTIME_CACHE_DIR.'~'.$class_name.'.php';
                    if ( ! file_exists($file)) {
                        // Return source with stripped comments and whitespace
                        file_put_contents($file, php_strip_whitespace($source_file));
                    }
                }
            }
            
            // Using require_once() in the __autoload() function is redundant.  
            // __autoload() is only called when php can't find your class definition.  
            // If your file containg your class was already included, the class defenition would already be loaded 
            // and __autoload() would not be called.  So save a little overhead and only use require() within __autoload()
            require $file;
        });
    }
    
    
}

// End of file: ./system/core/init.php