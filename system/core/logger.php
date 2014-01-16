<?php
/**
 * A light, permissions-checking logging class based on KLogger (https://github.com/katzgrau/KLogger)
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2014 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */

namespace InfoPotato\core;

class Logger {
    /**
     * We need a default argument value in order to add the ability to easily
     * print out the given argument. But we can't use NULL, 0, FALSE, etc, because those
     * are often the values the developers will test for. So we'll make one up.
     */
    const NO_ARG = 'NO_ARG';
    
    /**
     * Logging severity levels: 'ERROR' > 'WARNING' > 'INFO' > 'DEBUG'
     * From the most important priority(0) to the least important priority(3)
     * @var array
     */
    private static $severity_levels = array(
        'ERROR' => 3, // Error conditions
        'WARNING' => 2, // Warning conditions
        'INFO' => 1, // Informational messages
        'DEBUG' => 0, // Debug messages
    );
    
    /**
     * Use the lowest level 'DEBUG' as the default threshold
     * if APP_LOGGING_LEVEL_THRESHOLD not defined
     * @var string
     */
    private static $severity_threshold = 'DEBUG';

    /**
     * Standard messages produced by the class. Can be modified for il8n
     * @var array
     */
    private static $messages = array(
        'write_fail' => 'Failed to write the log message to log files. Please check file permissions to make it writable!',
        'open_fail' => 'Failed to open the log files. Please check permissions!',
        'invalid_level' => 'The logging severity level you provided is invalid!',
        'dir_permission' => 'Permission denied for creating the log directory!'
    );
    
    /**
     * Valid PHP date() format string for log timestamps
     * @var string
     */
    private static $date_format = 'Y-m-d G:i:s';
    
    /**
     * Private contructor prevents direct object creation
     * 
     * @return Logger
     */
    private function __construct() {}

    /**
     * Validate and initilize $severity_threshold
     * 
     * @return void
     */
    private static function initialize_severity_threshold() {
        // APP_LOGGING_LEVEL_THRESHOLD needs to be defined in bootstrap script
        // Severity levels: 'ERROR' > 'WARNING' > 'INFO' > 'DEBUG'
        if (defined('APP_LOGGING_LEVEL_THRESHOLD')) {
            if (isset(self::$severity_levels[APP_LOGGING_LEVEL_THRESHOLD])) {
                self::$severity_threshold = APP_LOGGING_LEVEL_THRESHOLD;
            } else {
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['invalid_level']);
            }
        }
    }

    /**
     * Runtime errors that do not require immediate action but 
     * should typically be logged and monitored
     * 
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_error($dir, $line, $arg = self::NO_ARG) {
        self::log($dir, $line, self::$severity_levels['ERROR'], $arg);
    }
    
    /**
     * Exceptional occurrences that are not errors. 
     * Examples: Use of deprecated APIs, poor use of an API, 
     * undesirable things that are not necessarily wrong.
     *
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_warning($dir, $line, $arg = self::NO_ARG) {
        self::log($dir, $line, self::$severity_levels['WARNING'], $arg);
    }
    
    /**
     * Interesting events. Examples: User logs in, SQL logs
     *
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_info($dir, $line, $arg = self::NO_ARG) {
        self::log($dir, $line, self::$severity_levels['INFO'], $arg);
    }
    
    /**
     * Detailed debug information
     *
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_debug($dir, $line, $arg = self::NO_ARG) {
        self::log($dir, $line, self::$severity_levels['DEBUG'], $arg);
    }
    
    /**
     * Writes a $line to the log with the given severity
     *
     * @param string  $dir File path to the logging directory
     * @param string  $line     Text to add to the log
     * @param integer $severity Severity level of log message (use constants)
     */
    private static function log($dir, $line, $severity, $arg = self::NO_ARG) {
        // Validate and initilize $severity_threshold
        self::initialize_severity_threshold();

        // Log only when severity level is higher than the defined severity threshold
        if ($severity >= self::$severity_levels[self::$severity_threshold]) {
            $dir = rtrim($dir, '\\/');
            
            // Log file path and name, e.g. log_2012-08-16.txt
            $log_file_path = $dir.DIRECTORY_SEPARATOR.'log_'.date('Y-m-d').'.txt';
            
            // Create the log directory first
            if ( ! file_exists($dir)) {
                // The thrid parameter TRUE allows the creation of nested directories specified in the path
                if ( ! mkdir($dir, 0777, TRUE)) {
                    // Output error message and terminate the current script
                    // Don't use halt() or any log functions in this class to avoid dead loop 
                    exit(self::$messages['dir_permission']);
                }
            }
            
            if (file_exists($log_file_path) && ! is_writable($log_file_path)) {
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['write_fail']);
            }
            
            // Returns a file pointer resource on success, or FALSE on error.
            if ( ! ($file_handle = fopen($log_file_path, 'a'))) {
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['open_fail']);
            }
            
            // Formatted time
            $time = date(self::$date_format);
            
            switch ($severity) {
                case self::$severity_levels['ERROR']:
                    $status = "$time - ERROR -->";
                    break;
                case self::$severity_levels['WARNING']:
                    $status = "$time - WARNING -->";
                    break;
                case self::$severity_levels['INFO']:
                    $status = "$time - INFO -->";
                    break;
                case self::$severity_levels['DEBUG']:
                    $status = "$time - DEBUG -->";
                    break;
            }
            
            $line = "$status $line";
            
            if ($arg !== self::NO_ARG) {
                // Prints to the log file with a dump of the given argument
                // var_export() with the second parameter TRUE returns the variable representation instead of outputting it
                $line = $line . '; ' . var_export($arg, TRUE);
            }

            // Writes a line to the log 
            // PHP_EOL: The correct 'End Of Line' symbol for this platform
            $line .= PHP_EOL;
            // Make sure the fwrite() writes all the bytes
            for ($written = 0, $length = strlen($line); $written < $length; $written += $result) {
                if (($result = fwrite($file_handle, $line)) === FALSE) {
                    break;
                }
            }
            
            if ($result === FALSE) {
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['write_fail']);
            }

            // Closes the open file pointer
            if ($file_handle) {
                fclose($file_handle);
            }
        }
    }
    
}

// End of file: ./system/core/logger.php