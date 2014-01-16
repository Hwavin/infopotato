<?php
/**
 * This file acts as the "Bootstrap" to your application, all requests are sent to index.php.
 * This file is responsible for initializing the framework: loading files, reading configuration data, 
 * parsing the URI into actionable information, and populating the objects that encapsulate the request. 
 */

/**
 * Define the application environment
 * Usage: development or production
 */
define('ENVIRONMENT', 'development');

/**
 * Set default time zone used by all date/time functions
 * List of Supported Timezones: http://us2.php.net/manual/en/timezones.php
 */
date_default_timezone_set('America/New_York');

/**
 * There are two types of requests, static requests and application requests.
 * STATIC_URI_BASE is used for static requests to access static assets (images) access, sometimes CDN is used
 * APP_URI_BASE is used for application requests to access resources that need InfoPotato to further process
 * Must end with the trailing slash '/'
 */
define('STATIC_URI_BASE', 'http://localhost/infopotato/application/public/');
define('APP_URI_BASE', 'http://localhost/infopotato/application/public/index.php/');

/**
 * Shorthand directory separator constant
 * On Windows, both slash (/) and backslash (\) are used as directory separator character. 
 * In other platforms, it is the forward slash (/).
 * DIRECTORY_SEPARATOR is not necessary, only the forward slash, '/' should be fine ???
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 * You can specify the absolute DIR path
 */
define('SYS_DIR', dirname(dirname(dirname(__FILE__).DS)).DS.'system'.DS);
define('SYS_CORE_DIR', SYS_DIR.'core'.DS);
define('SYS_LIBRARY_DIR', SYS_DIR.'libraries'.DS);
define('SYS_RUNTIME_CACHE_DIR', SYS_DIR.'runtime'.DS);

define('APP_DIR', dirname(dirname(__FILE__).DS).DS);
define('APP_LOG_DIR', APP_DIR.'logs'.DS);
define('APP_DATA_DIR', APP_DIR.'data'.DS);
define('APP_CONFIG_DIR', APP_DIR.'configs'.DS);
define('APP_MANAGER_DIR', APP_DIR.'managers'.DS);
define('APP_LIBRARY_DIR', APP_DIR.'libraries'.DS);
define('APP_TEMPLATE_DIR', APP_DIR.'templates'.DS);
define('APP_RUNTIME_CACHE_DIR', APP_DIR.'runtime'.DS);


/**
 * User-defined constants go here
 */
//define('APP_CACHE_DIR', APP_DIR.'cache'.DS);
//define('APP_UPLOAD_DIR', APP_DIR.'upload'.DS);
//define('APP_DOWNLOAD_DIR', APP_DIR.'downloads'.DS);
define('APP_SESSION_DIR', APP_DIR.'session'.DS);

/**
 * App namespaces (no leading and trailing backlash)
 */
define('APP_MANAGER_NAMESPACE', 'app\managers');
define('APP_DATA_NAMESPACE', 'app\data');
define('APP_LIBRARY_NAMESPACE', 'app\libraries');

/**
 * Default manager/method to use if none is given in the URL, lowercase
 */
define('APP_DEFAULT_MANAGER', 'home');
define('APP_DEFAULT_MANAGER_METHOD', 'index');

/**
 * Error 404 manager/method is designed for production mode
 * No need to show 404 error page in development mode
 */
define('APP_404_MANAGER', 'error');
define('APP_404_MANAGER_METHOD', '404');

/**
 * Sets the global logging severity level threshold
 * Severity levels: 'ERROR' > 'WARNING' > 'INFO' > 'DEBUG'
 */
define('APP_LOGGING_LEVEL_THRESHOLD', 'DEBUG');

/**
 * When turned-on, the system core components|libraries called will be cached and stored in SYS_RUNTIME_CACHE_DIR
 * When turned-on, the app managers|data|libraries called will be cached and stored in APP_RUNTIME_CACHE_DIR
 */
define('RUNTIME_CACHE', FALSE);

// Environment settings and autoloading
$file = SYS_CORE_DIR.'starter.php';

if (RUNTIME_CACHE === TRUE) {
    // SYS_RUNTIME_CACHE_DIR must be writable
    if ( ! is_writable(SYS_RUNTIME_CACHE_DIR) || ! is_writable(APP_RUNTIME_CACHE_DIR)) {
        exit('SYS_RUNTIME_CACHE_DIR and APP_RUNTIME_CACHE_DIR must be writable');
    }
    $file = SYS_RUNTIME_CACHE_DIR.'~starter.php';
    if ( ! file_exists($file)) {
        file_put_contents($file, php_strip_whitespace(SYS_CORE_DIR.'starter.php'));
    }
} 
require_once $file;

// Starter loads other core components
\InfoPotato\core\Starter::start();

// Dispatching
\InfoPotato\core\Dispatcher::run();

// End of file: ./index.php 

