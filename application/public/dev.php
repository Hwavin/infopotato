<?php
/**
 * This file acts as the "Bootstrap" to your application, all requests are sent to index.php.
 * This file is responsible for initializing the framework: loading files, reading configuration data, 
 * parsing the URI into actionable information, and populating the objects that encapsulate the request. 
 */

/**
 * Define the application environment
 * Usage: development or production, must lowercase
 */
define('ENVIRONMENT', 'development');

/**
 * Set default time zone used by all date/time functions
 * List of Supported Timezones: http://us2.php.net/manual/en/timezones.php
 */
date_default_timezone_set('America/New_York');

/**
 * When turned-on, the system core components|libraries called will be cached and stored in SYS_RUNTIME_CACHE_DIR
 * When turned-on, the app managers|data|libraries called will be cached and stored in APP_RUNTIME_CACHE_DIR
 */
define('RUNTIME_CACHE', FALSE);

/**
 * There are two types of requests, static requests and application requests.
 * STATIC_URI_BASE is used for static requests to access static assets (images) access, sometimes CDN is used
 * APP_URI_BASE is used for application requests to access resources that need InfoPotato to further process
 * Must end with the trailing slash '/'
 */
define('STATIC_URI_BASE', 'http://localhost/infopotato/application/public/');
define('APP_URI_BASE', 'http://localhost/infopotato/application/public/dev.php/');

/**
 * Shorthand directory separator constant
 * On Windows, both slash (/) and backslash (\) are used as directory separator character. 
 * In other platforms, it is the forward slash (/).
 * DIRECTORY_SEPARATOR is not necessarily needed as long as you use the forward slash (/),
 * BUT it's still useful for things like explode() a path that the system gave you.
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Framework system/application directories
 * You can specify the absolute DIR path too
 */
define('SYS_DIR', dirname(dirname(dirname(__FILE__).DS)).DS.'system'.DS);
define('SYS_CORE_DIR', SYS_DIR.'core'.DS);
define('SYS_LIBRARY_DIR', SYS_DIR.'libraries'.DS);
// Set this when you have RUNTIME_CACHE turned on and make it writable
define('SYS_RUNTIME_CACHE_DIR', SYS_DIR.'runtime'.DS);

define('APP_DIR', dirname(dirname(__FILE__).DS).DS);
define('APP_DATA_DIR', APP_DIR.'data'.DS);
define('APP_CONFIG_DIR', APP_DIR.'configs'.DS);
define('APP_MANAGER_DIR', APP_DIR.'managers'.DS);
define('APP_LIBRARY_DIR', APP_DIR.'libraries'.DS);
define('APP_TEMPLATE_DIR', APP_DIR.'templates'.DS);
// Set this when you have RUNTIME_CACHE turned on and make it writable
define('APP_RUNTIME_CACHE_DIR', APP_DIR.'runtime'.DS);

/**
 * Sets the logging directory and global logging severity level threshold
 * Severity levels: 'ERROR' > 'WARNING' > 'INFO' > 'DEBUG'
 * DEBUG is used by default if APP_LOGGING_LEVEL_THRESHOLD is not defined
 */
define('APP_LOG_DIR', APP_DIR.'logs'.DS);
define('APP_LOGGING_LEVEL_THRESHOLD', 'DEBUG');

/**
 * App namespaces (no leading and trailing backlash)
 */
define('APP_MANAGER_NAMESPACE', 'app\managers');
define('APP_DATA_NAMESPACE', 'app\data');
define('APP_LIBRARY_NAMESPACE', 'app\libraries');

/**
 * Default manager/method (case-insensitive) to use if none is given in the URL
 */
define('APP_DEFAULT_MANAGER', 'home');
define('APP_DEFAULT_MANAGER_METHOD', 'index');

/**
 * Error 404 manager/method (case-insensitive) designed for production mode
 * If not defined, the default 404 template packed with InfoPotato will be used
 * The actual error message will show instead of the 404 error page in development mode
 */
define('APP_404_MANAGER', 'error');
define('APP_404_MANAGER_METHOD', '404');

/**
 * Enable this if Session is used
 */
//define('APP_SESSION_DIR', APP_DIR.'session'.DS);

/**
 * Enable this if I18N is used
 */
//define('APP_I18N_DIR', APP_DIR.'i18n'.DS);
//define('APP_I18N_LANG', 'en_us');

/**
 * Other user-defined APP constants go here
 */
//define('APP_CACHE_DIR', APP_DIR.'cache'.DS);
//define('APP_UPLOAD_DIR', APP_DIR.'upload'.DS);
//define('APP_DOWNLOAD_DIR', APP_DIR.'downloads'.DS);

// Initial environment settings and autoloading
$starter_file = SYS_CORE_DIR.'starter.php';

if (RUNTIME_CACHE === TRUE) {
    if ( ! is_writable(SYS_RUNTIME_CACHE_DIR) || ! is_writable(APP_RUNTIME_CACHE_DIR)) {
        exit('SYS_RUNTIME_CACHE_DIR and APP_RUNTIME_CACHE_DIR must be writable');
    }
    $starter_file = SYS_RUNTIME_CACHE_DIR.'~starter.php';
    if ( ! file_exists($starter_file)) {
        file_put_contents($starter_file, php_strip_whitespace(SYS_CORE_DIR.'starter.php'));
    }
} 

require_once $starter_file;

// Starter loads other core components
\InfoPotato\core\Starter::start();

// Dispatching
\InfoPotato\core\Dispatcher::run();

// End of file: ./index.php 

