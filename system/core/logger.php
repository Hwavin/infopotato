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
     * Internal status codes
     */
    const STATUS_LOG_OPEN = 1;
    const STATUS_OPEN_FAILED = 2;
    const STATUS_LOG_CLOSED = 3;
    
    /**
     * We need a default argument value in order to add the ability to easily
     * print out objects etc. But we can't use NULL, 0, FALSE, etc, because those
     * are often the values the developers will test for. So we'll make one up.
     */
    const NO_ARGUMENTS = 'NO_ARGUMENTS';
    
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
     * Current minimum logging level threshold
     * @var integer
     */
    private static $severity_threshold;
    
    /**
     * Current status of the log file
     * @var integer
     */
    private static $log_status = self::STATUS_LOG_CLOSED;
    
    /**
     * Path to the log file
     * @var string
     */
    private static $log_file_path = NULL;
    
    /**
     * This holds the file handle for this instance's log file
     * @var resource
     */
    private static $file_handle = NULL;
    
    /**
     * Standard messages produced by the class. Can be modified for il8n
     * @var array
     */
    private static $messages = array(
        'write_fail' => 'Failed to write the log message to log files. Please check file permissions to make it writable!',
        'open_fail' => 'Failed to open the log files. Please check permissions!',
        'invalid_level' => 'The logging severity level you provided is invalid!',
    );
    
    /**
     * Valid PHP date() format string for log timestamps
     * @var string
     */
    private static $date_format = 'Y-m-d G:i:s';
    
    /**
     * Octal notation for default permissions of the log file
     * @var integer
     */
    private static $default_permissions = 0777;
    
    /**
     * Sets the default logging level
     * 
     * @return Logger
     */
    private function __construct() {
        // APP_LOGGING_LEVEL_THRESHOLD needs to be defined in bootstrap script
        // Severity levels: 'ERROR' > 'WARNING' > 'INFO' > 'DEBUG'
        if (defined('APP_LOGGING_LEVEL_THRESHOLD')) {
            if (isset(self::$severity_levels[APP_LOGGING_LEVEL_THRESHOLD])) {
                self::$severity_threshold = self::$severity_levels[APP_LOGGING_LEVEL_THRESHOLD];
            } else {
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['invalid_level']);
            }
            self::$severity_threshold = strtoupper(APP_LOGGING_LEVEL_THRESHOLD);
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
    public static function log_error($dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($dir, $line, self::$severity_levels['ERROR'], $args);
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
    public static function log_warning($dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($dir, $line, self::$severity_levels['WARNING'], $args);
    }
    
    /**
     * Interesting events. Examples: User logs in, SQL logs
     *
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_info($dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($dir, $line, self::$severity_levels['INFO'], $args);
    }
    
    /**
     * Detailed debug information
     *
     * @param string  $dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_debug($dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($dir, $line, self::$severity_levels['DEBUG'], $args);
    }
    
    /**
     * Writes a $line to the log with the given severity
     *
     * @param string  $dir File path to the logging directory
     * @param string  $line     Text to add to the log
     * @param integer $severity Severity level of log message (use constants)
     */
    private static function log($dir, $line, $severity, $args = self::NO_ARGUMENTS) {
        // Set the default logging severity level threshold if APP_LOGGING_LEVEL_THRESHOLD not defined
        if ( ! isset(self::$severity_threshold)) {
            // Use the lowest level as the threshold
            self::$severity_threshold = self::$severity_levels['DEBUG'];
        }
        
        // Log only when severity level is higher than the defined severity threshold
        if ($severity >= self::$severity_threshold) {
            $dir = rtrim($dir, '\\/');
            
            // Log file path and name, e.g. log_2012-08-16.txt
            self::$log_file_path = $dir.DIRECTORY_SEPARATOR.'log_'.date('Y-m-d').'.txt';
            
            // Create the log file first
            if ( ! file_exists($dir)) {
                mkdir($dir, self::$default_permissions, TRUE);
            }
            
            if (file_exists(self::$log_file_path) && ! is_writable(self::$log_file_path)) {
                self::$log_status = self::STATUS_OPEN_FAILED;
                // Output error message and terminate the current script
                // Don't use halt() or any log functions in this class to avoid dead loop 
                exit(self::$messages['write_fail']);
            }
            
            if ((self::$file_handle = fopen(self::$log_file_path, 'a'))) {
                self::$log_status = self::STATUS_LOG_OPEN;
            } else {
                self::$log_status = self::STATUS_OPEN_FAILED;
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
            
            if ($args !== self::NO_ARGUMENTS) {
                // Print the passed object value
                $line = $line . '; ' . var_export($args, TRUE);
            }
            
            // Writes a line to the log without prepending a status or timestamp
            if (self::$log_status === self::STATUS_LOG_OPEN) {
                // PHP_EOL: The correct 'End Of Line' symbol for this platform
                if (fwrite(self::$file_handle, $line.PHP_EOL) === FALSE) {
                    // Output error message and terminate the current script
                    // Don't use halt() or any log functions in this class to avoid dead loop 
                    exit(self::$messages['write_fail']);
                }
                
                // Closes the open file pointer
                if (self::$file_handle) {
                    fclose(self::$file_handle);
                }
            }
        }
    }
    
}

// End of file: ./system/core/logger.php