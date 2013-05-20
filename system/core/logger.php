<?php
/**
 * A light, permissions-checking logging class based on KLogger (https://github.com/katzgrau/KLogger)
 *
 * @author Zhou Yuan <yuanzhou19@gmail.com>
 * @link http://www.infopotato.com/
 * @copyright Copyright &copy; 2009-2013 Zhou Yuan
 * @license http://www.opensource.org/licenses/mit-license.php MIT Licence
 */
 
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
     * Logging severity levels, from the most important priority(0) to the least important priority(3).
     * @var array
     */
    private static $severity_levels = array(
        'ERROR' => 0, // Error: error conditions
        'WARN' => 1, // Warning: warning conditions
        'INFO' => 2, // Informational: informational messages
        'DEBUG' => 3, // Debug: debug messages
    );

    /**
     * Current minimum logging threshold
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
        'write_fail' => 'Failed to write the log message to log files. Please check file permissions to make it writable.',
        'open_fail' => 'Failed to open the log files. Please check permissions.',
        'invalid_level' => 'The logging severity level you provided is invalid',
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
     * Prevent direct object creation
     * 
     * @return Logger
     */
    private function __construct() {}
    
    /**
     * Sets the global severity threshold
     * 
     * @param string $level Valid severity level ('ERROR', 'WARN', 'INFO', 'DEBUG')
     */
    public static function set_severity_threshold($level) {
        if (isset(self::$severity_levels[$level])) {
            self::$severity_threshold = self::$severity_levels[$level];
        } else {
            // Output error message and terminate the current script
            // Don't use halt() or any log functions in this class to avoid dead loop 
            exit(self::$messages['invalid_level']);
        }
    }
    
    /**
     * Sets the date format
     * 
     * @param string $date_format Valid format string for date()
     */
    public static function set_date_format($date_format) {
        self::$date_format = $date_format;
    }

    /**
     * Writes a $line to the log with a severity level of ERR. Most likely used
     * with E_RECOVERABLE_ERROR
     *
     * @param string  $log_dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_error($log_dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($log_dir, $line, self::$severity_levels['ERROR'], $args);
    }
    
    /**
     * Writes a $line to the log with a severity level of WARN. Generally
     * corresponds to E_WARNING, E_USER_WARNING, E_CORE_WARNING, or 
     * E_COMPILE_WARNING
     *
     * @param string  $log_dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_warn($log_dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($log_dir, $line, self::$severity_levels['WARN'], $args);
    }

    /**
     * Writes a $line to the log with a severity level of INFO. Any information
     * can be used here, or it could be used with E_STRICT errors
     *
     * @param string  $log_dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_info($log_dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($log_dir, $line, self::$severity_levels['INFO'], $args);
    }

    /**
     * Writes a $line to the log with a severity level of DEBUG
     *
     * @param string  $log_dir File path to the logging directory
     * @param string $line Information to log
     * @return void
     */
    public static function log_debug($log_dir, $line, $args = self::NO_ARGUMENTS) {
        self::log($log_dir, $line, self::$severity_levels['DEBUG'], $args);
    }
    
    /**
     * Writes a $line to the log with the given severity
     *
     * @param string  $log_dir File path to the logging directory
     * @param string  $line     Text to add to the log
     * @param integer $severity Severity level of log message (use constants)
     */
    private static function log($log_dir, $line, $severity, $args = self::NO_ARGUMENTS) {
        // Set the default severity threshold in case set_severity_threshold() is not called in bootstrap 
        if ( ! isset(self::$severity_threshold)) {
            self::$severity_threshold = self::$severity_levels['DEBUG'];
        }
        
        // Log only when severity level is over the pre-defined severity threshold
        if ($severity <= self::$severity_threshold) {
            $log_dir = rtrim($log_dir, '\\/');

            // Log file path and name, e.g. log_2012-08-16.txt
            self::$log_file_path = $log_dir.DIRECTORY_SEPARATOR.'log_'.date('Y-m-d').'.txt';

            // Create the log file first
            if ( ! file_exists($log_dir)) {
                mkdir($log_dir, self::$default_permissions, TRUE);
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
                case self::$severity_levels['WARN']:
                    $status = "$time - WARN -->";
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